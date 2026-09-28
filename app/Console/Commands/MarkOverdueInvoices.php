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
    protected $description = 'Marquer comme en retard les factures échues et non payées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recherche des factures en retard...');

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
            $this->line("Facture {$invoice->number} marquée comme en retard");
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
            $this->line("Facture {$invoice->number} passée au statut payé");
        }

        $this->info("Terminé. {$count} facture(s) marquée(s) comme en retard.");

        return Command::SUCCESS;
    }
}

