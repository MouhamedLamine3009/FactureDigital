<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->type === 'quote' ? 'Devis' : 'Facture' }} {{ $document->number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700;800&display=swap');

        @page {
            margin: 0;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 10.5px;
            line-height: 1.35;
            color: #1e1e2a;
            background: #ffffff;
            -webkit-font-smoothing: antialiased;
        }

        .page {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
        }

        /* ────────────── BANDE LATÉRALE GAUCHE ────────────── */
        .accent-bar {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 8px;
            background: linear-gradient(180deg, #0a1628 0%, #1a2d4a 50%, #c8a45c 100%);
        }

        /* ────────────── EN-TÊTE PRINCIPAL ────────────── */
        .header {
            padding: 22px 40px 14px 44px;
            background: linear-gradient(165deg, #fcfaf7 0%, #f8f6f1 100%);
            border-bottom: 2px solid #e8e2d8;
            position: relative;
        }

        /* Ornement subtil en haut à droite */
        .header::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200,164,92,0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }

        /* Bloc entreprise */
        .company-block {
            flex: 1;
            max-width: 55%;
        }

        .logo-container {
            margin-bottom: 8px;
        }

        .company-logo {
            height: 42px;
            max-width: 160px;
            object-fit: contain;
        }

        .company-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 18px;
            font-weight: 700;
            color: #0a1628;
            letter-spacing: 0.2px;
            margin-bottom: 3px;
            line-height: 1.2;
        }

        .company-tagline {
            font-family: 'Inter', sans-serif;
            font-size: 8.5px;
            font-weight: 400;
            color: #c8a45c;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 6px;
        }

        .company-details {
            font-size: 9.5px;
            color: #6b7280;
            line-height: 1.5;
        }

        .company-details strong {
            color: #374151;
            font-weight: 500;
        }

        /* Titre du document */
        .doc-title-block {
            text-align: right;
            flex-shrink: 0;
        }

        .doc-type-wrapper {
            position: relative;
            display: inline-block;
        }

        .doc-type-label {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 30px;
            font-weight: 700;
            color: #0a1628;
            letter-spacing: 3px;
            line-height: 1;
            display: block;
        }

        .doc-type-underline {
            height: 3px;
            width: 60%;
            margin-left: auto;
            background: linear-gradient(90deg, transparent 0%, #c8a45c 100%);
            border-radius: 2px;
            margin-top: 4px;
            margin-bottom: 5px;
        }

        .doc-number {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        /* Badge statut */
        .status-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 8.5px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            background: #ffffff;
            border: 1.5px solid;
        }

        .status-draft {
            color: #9ca3af;
            border-color: #d1d5db;
            background: #f9fafb;
        }
        .status-sent {
            color: #2563eb;
            border-color: #93c5fd;
            background: #eff6ff;
        }
        .status-paid {
            color: #059669;
            border-color: #6ee7b7;
            background: #ecfdf5;
        }
        .status-overdue {
            color: #dc2626;
            border-color: #fca5a5;
            background: #fef2f2;
        }
        .status-viewed {
            color: #7c3aed;
            border-color: #c4b5fd;
            background: #f5f3ff;
        }
        .status-partial_paid {
            color: #d97706;
            border-color: #fcd34d;
            background: #fffbeb;
        }
        .status-cancelled {
            color: #6b7280;
            border-color: #d1d5db;
            background: #f3f4f6;
        }
        .status-accepted {
            color: #059669;
            border-color: #6ee7b7;
            background: #ecfdf5;
        }
        .status-refused {
            color: #dc2626;
            border-color: #fca5a5;
            background: #fef2f2;
        }

        /* ────────────── BANDE DATES ────────────── */
        .dates-band {
            padding: 9px 40px 9px 44px;
            background: #ffffff;
            display: flex;
            gap: 28px;
            border-bottom: 1px solid #f0ece6;
        }

        .date-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
            position: relative;
        }

        .date-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: -14px;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 20px;
            background: #e5e0d8;
        }

        .date-label {
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #9ca3af;
            font-weight: 600;
        }

        .date-value {
            font-size: 11px;
            font-weight: 600;
            color: #0a1628;
        }

        /* ────────────── CORPS PRINCIPAL ────────────── */
        .body-section {
            padding: 16px 40px 12px 44px;
        }

        /* ── Parties : Emetteur / Client ── */
        .parties {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 14px;
        }

        .party {
            flex: 1;
        }

        .party-card {
            border: 1px solid #e5e0d8;
            border-radius: 10px;
            padding: 10px 14px;
            background: #fcfaf7;
            height: 100%;
            page-break-inside: avoid;
        }

        .party-card.client-card {
            background: #ffffff;
            border: 1px solid #e5e0d8;
            border-left: 3px solid #c8a45c;
        }

        .party-label {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #c8a45c;
            margin-bottom: 5px;
        }

        .party-name {
            font-size: 13px;
            font-weight: 700;
            color: #0a1628;
            margin-bottom: 3px;
            font-family: 'Playfair Display', Georgia, serif;
        }

        .party-details {
            font-size: 9.5px;
            color: #6b7280;
            line-height: 1.5;
        }

        .party-details .highlight {
            color: #374151;
            font-weight: 500;
        }

        /* ── Tableau des lignes ── */
        .items-table-wrapper {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e8e2d8;
            margin-bottom: 14px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead {
            display: table-header-group;
        }

        .items-table thead tr {
            background: #0a1628;
        }

        .items-table th {
            padding: 9px 12px;
            text-align: left;
            font-size: 8.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: rgba(255,255,255,0.85);
        }

        .items-table th:first-child {
            padding-left: 16px;
        }

        .items-table th:last-child {
            padding-right: 16px;
        }

        .items-table th.text-right,
        .items-table td.text-right {
            text-align: right;
        }

        .items-table th.text-center,
        .items-table td.text-center {
            text-align: center;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #f0ece6;
            page-break-inside: avoid;
        }

        .items-table tbody tr:last-child {
            border-bottom: none;
        }

        .items-table tbody tr:nth-child(even) {
            background: #faf8f5;
        }

        .items-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .items-table td {
            padding: 7px 12px;
            font-size: 10.5px;
            color: #374151;
            vertical-align: top;
        }

        .items-table td:first-child {
            padding-left: 16px;
        }

        .items-table td:last-child {
            padding-right: 16px;
        }

        .item-description {
            font-weight: 600;
            color: #111827;
        }

        .item-description-sub {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 2px;
            font-weight: 400;
        }

        /* Footer du tableau (ligne total) */
        .items-table tfoot tr {
            background: #f7f4ef;
        }

        .items-table tfoot td {
            padding: 8px 12px;
            font-weight: 600;
            font-size: 10.5px;
            color: #0a1628;
            border-top: 2px solid #e8e2d8;
        }

        .items-table tfoot td:first-child {
            padding-left: 16px;
        }

        .items-table tfoot td:last-child {
            padding-right: 16px;
        }

        /* ────────────── SECTION BASSE : PAIEMENT + TOTAUX ────────────── */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        /* Infos de paiement - style carte premium */
        .payment-info {
            flex: 1;
        }

        .payment-title {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #9ca3af;
            margin-bottom: 6px;
        }

        .payment-card {
            background: #fcfaf7;
            border: 1px solid #e5e0d8;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 9.5px;
            color: #374151;
            line-height: 1.4;
            page-break-inside: avoid;
        }

        .payment-card .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            border-bottom: 1px dashed #e8e2d8;
        }

        .payment-card .payment-row:last-child {
            border-bottom: none;
        }

        .payment-card .payment-key {
            color: #9ca3af;
            font-weight: 500;
            font-size: 9px;
        }

        .payment-card .payment-val {
            font-weight: 600;
            color: #0a1628;
            text-align: right;
            max-width: 55%;
        }

        /* Bloc totaux - design premium */
        .totals-block {
            width: 48%;
            flex-shrink: 0;
        }

        .totals-inner {
            background: #ffffff;
            border: 1px solid #e5e0d8;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(10,22,40,0.04);
            page-break-inside: avoid;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 14px;
            font-size: 10.5px;
            border-bottom: 1px solid #f3f1ee;
        }

        .totals-row:last-child {
            border-bottom: none;
        }

        .totals-label {
            color: #6b7280;
            font-weight: 500;
        }

        .totals-value {
            font-weight: 600;
            color: #374151;
        }

        .totals-discount .totals-value {
            color: #059669;
        }

        /* Ligne TOTAL TTC - mise en évidence */
        .totals-grand {
            background: #0a1628;
            padding: 10px 14px;
        }

        .totals-grand .totals-label {
            color: rgba(255,255,255,0.75);
            font-size: 11px;
            font-weight: 600;
        }

        .totals-grand .totals-value {
            color: #ffffff;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.3px;
        }

        /* Reste a payer */
        .totals-balance {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-top: 2px solid #f59e0b;
        }

        .totals-balance .totals-label {
            color: #92400e;
            font-weight: 600;
        }

        .totals-balance .totals-value {
            color: #92400e;
            font-size: 13px;
            font-weight: 700;
        }

        /* ────────────── NOTES & CONDITIONS ────────────── */
        .notes-section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .notes-grid {
            display: flex;
            gap: 14px;
        }

        .note-block {
            flex: 1;
            background: #fcfaf7;
            border: 1px solid #e5e0d8;
            border-radius: 10px;
            padding: 10px 14px;
            page-break-inside: avoid;
        }

        .note-title {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #c8a45c;
            margin-bottom: 5px;
        }

        .note-content {
            font-size: 9.5px;
            color: #6b7280;
            line-height: 1.4;
        }

        /* ────────────── SIGNATURE ────────────── */
        .signature-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 220px;
            text-align: center;
        }

        .signature-label {
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #9ca3af;
            font-weight: 600;
            margin-bottom: 18px;
        }

        /* Si la société a une signature, l'afficher */
        .signature-image {
            max-height: 50px;
            max-width: 150px;
            object-fit: contain;
            margin-bottom: 6px;
        }

        .signature-line {
            border-top: 1.5px solid #d1d5db;
            padding-top: 4px;
            font-size: 9.5px;
            color: #6b7280;
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
        }

        /* ────────────── PIED DE PAGE ────────────── */
        .footer {
            background: #0a1628;
            padding: 12px 40px 12px 44px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: rgba(255,255,255,0.7);
            font-size: 8.5px;
            position: relative;
        }

        /* Ligne or subtile */
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 44px;
            right: 40px;
            height: 2px;
            background: linear-gradient(90deg, #c8a45c, transparent 60%);
        }

        .footer-left {
            line-height: 1.5;
        }

        .footer-left a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }

        .footer-right {
            text-align: right;
            line-height: 1.5;
        }

        .footer-brand {
            font-size: 7.5px;
            color: rgba(255,255,255,0.4);
            margin-top: 3px;
            letter-spacing: 0.5px;
        }

        .footer-brand span {
            color: rgba(255,255,255,0.6);
        }

        /* ────────────── WATERMARK (optionnel) ────────────── */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 80px;
            font-weight: 700;
            color: rgba(10,22,40,0.03);
            pointer-events: none;
            z-index: -1;
            white-space: nowrap;
        }
    </style>
</head>
<body>

<div class="page">

    {{-- Watermark subtil sur les documents payes ou brouillons --}}
    @if($document->status === 'paid')
        <div class="watermark">PAYÉ</div>
    @elseif($document->status === 'draft')
        <div class="watermark">BROUILLON</div>
    @endif

    {{-- Bande laterale decorative gauche --}}
    <div class="accent-bar"></div>

    {{-- EN-TETE --}}
    <div class="header">
        <div class="header-inner">
            {{-- Bloc Entreprise --}}
            <div class="company-block">
                @if($company->logo_path)
                    <div class="logo-container">
                        <img src="{{ public_path('storage/' . $company->logo_path) }}"
                             alt="Logo {{ $company->name }}"
                             class="company-logo">
                    </div>
                @endif
                <div class="company-name">{{ $company->legal_name ?: $company->name }}</div>
                <div class="company-tagline">{{ $company->city ? strtoupper($company->city) : 'ENTREPRISE' }}</div>
                <div class="company-details">
                    <strong>{{ $company->address }}</strong><br>
                    {{ $company->postal_code }} {{ $company->city }}
                    @if($company->country && $company->country !== 'Sénégal'), {{ $company->country }}@endif
                </div>
            </div>

            {{-- Titre du document --}}
            <div class="doc-title-block">
                <div class="doc-type-wrapper">
                    <span class="doc-type-label">
                        {{ $document->type === 'quote' ? 'DEVIS' : 'FACTURE' }}
                    </span>
                    <div class="doc-type-underline"></div>
                </div>
                <div class="doc-number">Réf. {{ $document->number }}</div>
                <span class="status-badge status-{{ $document->status }}">
                    @switch($document->status)
                        @case('draft')         Brouillon   @break
                        @case('sent')          Envoyé      @break
                        @case('viewed')        Vu          @break
                        @case('accepted')      Accepté     @break
                        @case('refused')       Refusé      @break
                        @case('paid')          Payé        @break
                        @case('partial_paid')  Partiel     @break
                        @case('overdue')       En retard   @break
                        @case('cancelled')     Annulé      @break
                        @default {{ ucfirst($document->status) }}
                    @endswitch
                </span>
            </div>
        </div>
    </div>

    {{-- BANDE DATES --}}
    <div class="dates-band">
        <div class="date-item">
            <span class="date-label">Date d'émission</span>
            <span class="date-value">{{ $document->issue_date->format('d/m/Y') }}</span>
        </div>
        <div class="date-item">
            <span class="date-label">Date d'échéance</span>
            <span class="date-value">{{ $document->due_date->format('d/m/Y') }}</span>
        </div>
        @if($document->payment_terms)
        <div class="date-item">
            <span class="date-label">Règlement</span>
            <span class="date-value">Sous {{ $document->payment_terms }} jours</span>
        </div>
        @endif
        <div class="date-item">
            <span class="date-label">Devise</span>
            <span class="date-value">{{ $company->currency_symbol ?? 'FCFA' }}</span>
        </div>
    </div>

    {{-- CORPS PRINCIPAL --}}
    <div class="body-section">

        {{-- EMETTEUR / CLIENT --}}
        <div class="parties">
            <div class="party">
                <div class="party-card">
                    <div class="party-label">Émetteur</div>
                    <div class="party-name">{{ $company->legal_name ?: $company->name }}</div>
                    <div class="party-details">
                        {{ $company->address }}<br>
                        {{ $company->postal_code }} {{ $company->city }}<br>
                        @if($company->email)<span class="highlight">{{ $company->email }}</span><br>@endif
                        @if($company->phone){{ $company->phone }}<br>@endif
                        @if($company->ninea)<strong>NINEA :</strong> {{ $company->ninea }}<br>@endif
                        @if($company->rccm)<strong>RC :</strong> {{ $company->rccm }}@endif
                    </div>
                </div>
            </div>
            <div class="party">
                <div class="party-card client-card">
                    <div class="party-label">Facturé à</div>
                    <div class="party-name">{{ $client->name }}</div>
                    <div class="party-details">
                        @if($client->contact_name)<span class="highlight">{{ $client->contact_name }}</span><br>@endif
                        {{ $client->address }}<br>
                        {{ $client->postal_code }} {{ $client->city }}<br>
                        @if($client->country && $client->country !== 'France'){{ $client->country }}<br>@endif
                        @if($client->email){{ $client->email }}<br>@endif
                        @if($client->phone){{ $client->phone }}@endif
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLEAU DES ARTICLES --}}
        <div class="items-table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:42%">Description</th>
                        <th class="text-center" style="width:8%">Qté</th>
                        <th class="text-right" style="width:16%">Prix Unit. HT</th>
                        <th class="text-center" style="width:8%">TVA</th>
                        <th class="text-right" style="width:16%">Total HT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($document->items as $item)
                    <tr>
                        <td>
                            <div class="item-description">{{ $item->description }}</div>
                            @if(isset($item->note) && $item->note)
                                <div class="item-description-sub">{{ $item->note }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 0, ',', ' ') }}</td>
                        <td class="text-center">{{ $item->tax_rate }}%</td>
                        <td class="text-right">{{ number_format($item->total, 0, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAIEMENT + TOTAUX --}}
        <div class="bottom-section">

            {{-- Informations bancaires --}}
            <div class="payment-info">
                <div class="payment-title">Informations de paiement</div>
                <div class="payment-card">
                    @if(isset($company->bank_name) && $company->bank_name)
                    <div class="payment-row">
                        <span class="payment-key">Banque</span>
                        <span class="payment-val">{{ $company->bank_name }}</span>
                    </div>
                    @endif
                    @if(isset($company->bank_account_name) && $company->bank_account_name)
                    <div class="payment-row">
                        <span class="payment-key">Titulaire</span>
                        <span class="payment-val">{{ $company->bank_account_name }}</span>
                    </div>
                    @endif
                    @if(isset($company->bank_account_number) && $company->bank_account_number)
                    <div class="payment-row">
                        <span class="payment-key">N° Compte</span>
                        <span class="payment-val">{{ $company->bank_account_number }}</span>
                    </div>
                    @endif
                    @if(isset($company->bank_iban) && $company->bank_iban)
                    <div class="payment-row">
                        <span class="payment-key">IBAN</span>
                        <span class="payment-val" style="font-size:9px;word-break:break-all;">{{ $company->bank_iban }}</span>
                    </div>
                    @endif
                    @if(isset($company->bank_bic) && $company->bank_bic)
                    <div class="payment-row">
                        <span class="payment-key">BIC</span>
                        <span class="payment-val">{{ $company->bank_bic }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Totaux --}}
            <div class="totals-block">
                <div class="totals-inner">
                    <div class="totals-row">
                        <span class="totals-label">Sous-total HT</span>
                        <span class="totals-value">{{ number_format($document->subtotal, 0, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                    </div>
                    @if($document->discount_amount > 0)
                    <div class="totals-row totals-discount">
                        <span class="totals-label">Remise</span>
                        <span class="totals-value">− {{ number_format($document->discount_amount, 0, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                    </div>
                    @endif
                    <div class="totals-row">
                        <span class="totals-label">TVA</span>
                        <span class="totals-value">{{ number_format($document->tax_amount, 0, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                    </div>
                    <div class="totals-row totals-grand">
                        <span class="totals-label">Total TTC</span>
                        <span class="totals-value">{{ number_format($document->total, 0, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                    </div>
                    @if($document->paid_amount > 0)
                    <div class="totals-row">
                        <span class="totals-label">Déjà payé</span>
                        <span class="totals-value">− {{ number_format($document->paid_amount, 0, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                    </div>
                    <div class="totals-row totals-balance">
                        <span class="totals-label">Reste à payer</span>
                        <span class="totals-value">{{ number_format($document->balance, 0, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- NOTES & CONDITIONS --}}
        @if($document->notes || $document->terms_conditions)
        <div class="notes-section">
            <div class="notes-grid">
                @if($document->notes)
                <div class="note-block">
                    <div class="note-title">Notes</div>
                    <div class="note-content">{{ $document->notes }}</div>
                </div>
                @endif
                @if($document->terms_conditions)
                <div class="note-block">
                    <div class="note-title">Conditions de règlement</div>
                    <div class="note-content">{{ $document->terms_conditions }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- SIGNATURE --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Signature &amp; Cachet</div>
                @if($company->signature_path)
                <div>
                    <img src="{{ public_path('storage/' . $company->signature_path) }}"
                         alt="Signature"
                         class="signature-image">
                </div>
                @endif
                <div class="signature-line">Bon pour accord</div>
            </div>
        </div>

    </div>{{-- /body-section --}}

    {{-- PIED DE PAGE --}}
    <div class="footer">
        <div class="footer-left">
            @if($company->email)<a href="mailto:{{ $company->email }}">{{ $company->email }}</a><br>@endif
            @if($company->phone){{ $company->phone }}<br>@endif
            @if($company->website)<a href="{{ $company->website }}">{{ $company->website }}</a>@endif
        </div>
        <div class="footer-right">
            @if($company->ninea)NINEA : {{ $company->ninea }}<br>@endif
            @if($company->rccm)RC : {{ $company->rccm }}<br>@endif
            @if($company->vat_number)TVA : {{ $company->vat_number }}<br>@endif
            <div class="footer-brand">
                {{ $document->type === 'quote' ? 'Devis' : 'Facture' }} généré via <span>{{ config('app.name') }}</span>
            </div>
        </div>
    </div>

</div>{{-- /page --}}

</body>
</html>