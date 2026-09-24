<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Document;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{

    public $period = 'month';
    public $activeRangeLabel = null;
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

    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * Résout la période active (début, fin, granularité) selon le présélection.
     */
    private function resolvePeriod(): array
    {
        $now = now();

        return match ($this->period) {
            'week' => [
                'start' => $now->copy()->subWeek()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'granularity' => 'day',
            ],
            'threemonths' => [
                'start' => $now->copy()->subMonths(3)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'granularity' => 'week',
            ],
            'sixmonths' => [
                'start' => $now->copy()->subMonths(6)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'granularity' => 'month',
            ],
            'year' => [
                'start' => $now->copy()->subYear()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'granularity' => 'month',
            ],
            default => [
                'start' => $now->copy()->subMonth()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'granularity' => 'day',
            ],
        };
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
                'refused_quotes' => 0,
                'clients_donut' => ['actifs' => 0, 'inactifs' => 0],
                'invoices_donut' => ['payees' => 0, 'en_attente' => 0, 'en_retard' => 0],
                'chart' => collect([]),
                'chart_subtitle' => 'Revenus mensuels',
            ];
            $this->activeRangeLabel = null;
            return;
        }

        $companyId = $this->company->id;
        $today = now()->format('Y-m-d');

        $period = $this->resolvePeriod();
        $start = $period['start'];
        $end = $period['end'];
        $granularity = $period['granularity'];

        $dateRange = [$start->toDateString(), $end->toDateString()];
        $tsRange = [$start->copy()->toDateTimeString(), $end->copy()->toDateTimeString()];

        // Revenus : factures payées sur la période (encaissées, date = paid_at)
        $this->stats['revenue'] = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->where('status', 'paid')
            ->whereBetween('paid_at', $tsRange)
            ->sum('total');

        // En retard : factures émises sur la période, désormais échues
        $this->stats['overdue'] = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->whereBetween('issue_date', $dateRange)
            ->whereNotIn('status', ['paid', 'cancelled', 'draft'])
            ->where('due_date', '<', $today)
            ->count();

        // En attente : factures émises sur la période, encore dans les délais
        $this->stats['pending'] = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->whereBetween('issue_date', $dateRange)
            ->whereIn('status', ['sent', 'partial_paid'])
            ->where('due_date', '>=', $today)
            ->count();

        // Devis (émis sur la période) — 1 requête agrégée par statut
        $quoteRow = Document::where('company_id', $companyId)
            ->where('type', 'quote')
            ->whereBetween('issue_date', $dateRange)
            ->selectRaw("
                COUNT(CASE WHEN status IN ('draft','sent','viewed') THEN 1 END) as en_cours,
                COUNT(CASE WHEN status = 'accepted' THEN 1 END) as acceptes,
                COUNT(CASE WHEN status = 'refused' THEN 1 END) as refuses
            ")
            ->first();

        $this->stats['quotes'] = (int) $quoteRow->en_cours;
        $this->stats['accepted_quotes'] = (int) $quoteRow->acceptes;
        $this->stats['refused_quotes'] = (int) $quoteRow->refuses;

        // Clients créés sur la période — 1 requête agrégée (total + actifs)
        $clientRow = Client::where('company_id', $companyId)
            ->whereBetween('created_at', $tsRange)
            ->selectRaw('COUNT(*) as total, COALESCE(SUM(is_active), 0) as actifs')
            ->first();

        $this->stats['clients_count'] = (int) $clientRow->total;
        $this->stats['clients_donut'] = [
            'actifs' => (int) $clientRow->actifs,
            'inactifs' => (int) $clientRow->total - (int) $clientRow->actifs,
        ];

        // Factures créées sur la période
        $this->stats['invoices_count'] = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->whereBetween('created_at', $tsRange)
            ->count();

        // Taux de paiement + donut factures — 1 requête agrégée
        $invoiceRow = Document::where('company_id', $companyId)
            ->where('type', 'invoice')
            ->whereBetween('issue_date', $dateRange)
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->selectRaw('COUNT(*) as total, COALESCE(SUM(status = \'paid\'), 0) as paid')
            ->first();

        $totalInvoices = (int) $invoiceRow->total;
        $paidInvoices = (int) $invoiceRow->paid;

        $this->stats['payment_rate'] = $totalInvoices > 0
            ? round(($paidInvoices / $totalInvoices) * 100, 1)
            : 0;

        // Répartition des factures par statut (payées / en attente / en retard)
        $this->stats['invoices_donut'] = [
            'payees' => $paidInvoices,
            'en_attente' => $this->stats['pending'],
            'en_retard' => $this->stats['overdue'],
        ];

        // Graphique d'évolution du chiffre d'affaires (granularité adaptative)
        $this->stats['chart'] = collect($this->buildRevenueSeries($start, $end, $granularity));

        $this->activeRangeLabel = 'du ' . $start->format('d/m/Y') . ' au ' . $end->format('d/m/Y');
        $this->stats['chart_subtitle'] = 'Revenus ' . $this->granularityLabel($granularity)
            . ' du ' . $start->format('d/m/Y') . ' au ' . $end->format('d/m/Y');
    }

    /**
     * Série mensuelle/jour/hebdo du chiffre d'affaires sur la période.
     */
    private function buildRevenueSeries(Carbon $start, Carbon $end, string $granularity): array
    {
        $rows = Document::where('company_id', $this->company->id)
            ->where('type', 'invoice')
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$start->copy()->toDateTimeString(), $end->copy()->toDateTimeString()])
            ->select(DB::raw('date(paid_at) as d'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('date(paid_at)'))
            ->orderBy('d')
            ->pluck('total', 'd');

        return $this->bucketize($start, $end, $granularity, $rows);
    }

    /**
     * Découpe la période en cases (jour / semaine / mois) remplies à 0.
     */
    private function bucketize(Carbon $start, Carbon $end, string $granularity, $rows): array
    {
        $series = [];

        if ($granularity === 'day') {
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $key = $d->format('Y-m-d');
                $series[] = [
                    'key' => $key,
                    'label' => $this->frDayLabel($d),
                    'value' => (float) ($rows[$key] ?? 0),
                ];
            }

            return $series;
        }

        $buckets = [];

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($granularity === 'week') {
                $weekStart = $d->copy()->startOfWeek();
                $k = $weekStart->format('Y-m-d');
                $label = 'Sem. ' . $this->frDayLabel($weekStart);
            } else {
                $k = $d->format('Y-m');
                $label = $this->frMonthLabel($k);
            }

            $buckets[$k] ??= ['label' => $label, 'value' => 0];
            $buckets[$k]['value'] += (float) ($rows[$d->format('Y-m-d')] ?? 0);
        }

        foreach ($buckets as $k => $bucket) {
            $series[] = ['key' => $k, 'label' => $bucket['label'], 'value' => $bucket['value']];
        }

        return $series;
    }

    private function granularityLabel(string $granularity): string
    {
        return match ($granularity) {
            'day' => 'quotidiens',
            'week' => 'hebdomadaires',
            default => 'mensuels',
        };
    }

    private function frShortMonth(int $month): string
    {
        return ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'][$month - 1];
    }

    private function frDayLabel(Carbon $date): string
    {
        return $date->day . ' ' . $this->frShortMonth($date->month);
    }

    private function frMonthLabel(string $yearMonth): string
    {
        [$year, $month] = array_map('intval', explode('-', $yearMonth));

        return ucfirst($this->frShortMonth($month)) . ' ' . $year;
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