<?php

namespace App\Livewire;

use App\Models\Document;
use DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{

    public $period = 'month';
    public $stats = [];
    public $recentDocuments;
    public $company;

    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function mount()
    {
        $this->loadCompany();
        $this->loadStats();
        $this->loadRecentDocuments();
    }

    public function loadCompany()
    {
        // First try to get current company from user
        $this->company = auth()->user()->currentCompany;

        // If no current company, try to get first company
        if (!$this->company) {
            $this->company = auth()->user()->companies()->first();
        }
    }

    public function updatedPeriod()
    {
        $this->loadStats();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'period') {
            $this->loadStats();
        }
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        $this->loadStats();
    }

    public function loadStats()
    {
        if (!$this->company) {
            $this->stats = [
                'revenue' => 0,
                'overdue' => 0,
                'pending' => 0,
                'quotes' => 0,
                'clients_count' => 0,
                'invoices_count' => 0,
                'payment_rate' => 0,
                'accepted_quotes' => 0,
                'chart' => collect([]),
            ];
            return;
        }

        $companyId = $this->company->id;
        $today = now()->format('Y-m-d');

        $dateRange = match ($this->period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };

        // Chiffres d'affaires - Sum of paid invoices total (using paid_at date)
        $this->stats['revenue'] = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->where('status', 'paid')
            ->where('paid_at', '>=', $dateRange)
            ->sum('total');

        // Factures en retard - Invoices with due_date in the past (automatic detection based on due_date)
        // Automatically count as overdue if due_date is in the past and not paid/cancelled/draft
        $this->stats['overdue'] = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->whereNotIn('status', ['paid', 'cancelled', 'draft'])
            ->where('due_date', '<', $today)
            ->count();

        // En attente de paiement - Invoices sent but not overdue yet
        // Includes sent, partial_paid, accepted quotes that haven't been converted
        // Note: invoices with 'overdue' status are counted in overdue, not here
        $this->stats['pending'] = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->whereIn('status', ['sent', 'partial_paid'])
            ->where('due_date', '>=', $today)
            ->count();

        // Devis en attente - Quotes that are draft, sent or viewed (not accepted/refused/converted)
        $this->stats['quotes'] = Document::where('company_id', $companyId)
            ->where('type', 'quote')
            ->whereIn('status', ['draft', 'sent', 'viewed'])
            ->count();

        // Nombre de clients
        $this->stats['clients_count'] = \App\Models\Client::where('company_id', $companyId)->count();

        // Nombre de factures créées cette période
        $this->stats['invoices_count'] = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->where('created_at', '>=', $dateRange)
            ->count();

        // Taux de paiement - Percentage of paid invoices
        $totalInvoices = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->count();

        $paidInvoices = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->where('status', 'paid')
            ->count();

        $this->stats['payment_rate'] = $totalInvoices > 0
            ? round(($paidInvoices / $totalInvoices) * 100, 1)
            : 0;

        // Devis acceptés - Accepted quotes count
        $this->stats['accepted_quotes'] = Document::where('company_id', $companyId)
            ->where('type', 'quote')
            ->where('status', 'accepted')
            ->count();

        // Graphique d'évolution - Get monthly revenue for last 6 months (using paid_at date)
        $dateFormat = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', paid_at)"
            : "DATE_FORMAT(paid_at, '%Y-%m')";

        // Get monthly revenue for last 6 months
        $monthlyRevenue = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw($dateFormat . ' as month'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Convert to array with evolution percentage
        $monthlyData = [];
        $previousTotal = null;

        foreach ($monthlyRevenue as $item) {
            $monthName = \Carbon\Carbon::createFromFormat('Y-m', $item->month)->format('F Y');
            $evolution = null;

            if ($previousTotal !== null && $previousTotal > 0) {
                $evolution = round((($item->total - $previousTotal) / $previousTotal) * 100, 1);
            }

            $monthlyData[] = [
                'month' => $monthName,
                'month_key' => $item->month,
                'total' => $item->total,
                'evolution' => $evolution
            ];

            $previousTotal = $item->total;
        }

        $this->stats['chart'] = collect($monthlyData);
    }

    public function loadRecentDocuments()
    {
        if (!$this->company) {
            $this->recentDocuments = collect([]);
            return;
        }

        $companyId = $this->company->id;

        $this->recentDocuments = Document::with('client')
            ->where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

