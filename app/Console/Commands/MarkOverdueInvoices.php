<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;

class MarkOverdueInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:mark-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark invoices as overdue if past due date and not paid';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue invoices...');

        // Find invoices that are past due date and not already marked as paid, cancelled, or overdue
        $overdueInvoices = Document::where('type', 'invoice')
            ->whereNotIn('status', ['paid', 'cancelled', 'overdue', 'draft'])
            ->where('due_date', '<', now()->toDateString())
            ->get();

        $count = 0;

        foreach ($overdueInvoices as $invoice) {
            $invoice->update([
                'status' => 'overdue',
            ]);
            $count++;
            $this->line("Marked invoice {$invoice->number} as overdue");
        }

        // Also mark invoices that were previously marked as overdue but are now paid
        $invoicesWithPayments = Document::where('type', 'invoice')
            ->where('status', 'overdue')
            ->where('balance', '<=', 0)
            ->get();

        foreach ($invoicesWithPayments as $invoice) {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
            $this->line("Updated invoice {$invoice->number} to paid status");
        }

        $this->info("Completed. Marked {$count} invoices as overdue.");

        return Command::SUCCESS;
    }
}

