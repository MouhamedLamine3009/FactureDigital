<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->type === 'quote' ? 'Devis' : 'Facture' }} {{ $document->number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #1f2937;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .company-info {
            max-width: 50%;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 11px;
            color: #6b7280;
        }
        
        .document-info {
            text-align: right;
        }
        
        .document-title {
            font-size: 24px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }
        
        .document-number {
            font-size: 14px;
            color: #6b7280;
        }
        
        .dates {
            margin-top: 10px;
            font-size: 11px;
            color: #6b7280;
        }
        
        .parties {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .party {
            width: 45%;
        }
        
        .party-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .party-name {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 3px;
        }
        
        .party-details {
            font-size: 11px;
            color: #6b7280;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background-color: #f3f4f6;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .items-table th.text-right {
            text-align: right;
        }
        
        .items-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        .items-table td.text-right {
            text-align: right;
        }
        
        .items-table tbody tr:hover {
            background-color: #f9fafb;
        }
        
        .totals {
            margin-left: auto;
            width: 250px;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 11px;
        }
        
        .totals-row.total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #e5e7eb;
            padding-top: 12px;
            margin-top: 4px;
        }
        
        .notes-terms {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .notes-terms-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 8px;
        }
        
        .notes-terms-content {
            font-size: 10px;
            color: #6b7280;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .badge-draft {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        
        .badge-sent {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        
        .badge-paid {
            background-color: #d1fae5;
            color: #059669;
        }
        
        .badge-overdue {
            background-color: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">{{ $company->legal_name ?: $company->name }}</div>
                <div class="company-details">
                    {{ $company->address }}<br>
                    {{ $company->postal_code }} {{ $company->city }}<br>
                    @if($company->email){{ $company->email }}<br>@endif
                    @if($company->phone){{ $company->phone }}<br>@endif
                    @if($company->ninea)NINEA: {{ $company->ninea }}<br>@endif
                    @if($company->rc)RC: {{ $company->rc }}@endif
                </div>
            </div>
            <div class="document-info">
                <div class="document-title">
                    {{ $document->type === 'quote' ? 'DEVIS' : 'FACTURE' }}
                </div>
                <div class="document-number">N° {{ $document->number }}</div>
                <span class="badge badge-{{ $document->status }}">
                    @if($document->status === 'draft')Brouillon
                    @elseif($document->status === 'sent')Envoyé
                    @elseif($document->status === 'paid')Payé
                    @elseif($document->status === 'overdue')En retard
                    @else{{ ucfirst($document->status) }}
                    @endif
                </span>
                <div class="dates">
                    <div>Date d'émission: {{ $document->issue_date->format('d/m/Y') }}</div>
                    <div>Échéance: {{ $document->due_date->format('d/m/Y') }}</div>
                    @if($document->payment_terms)
                    <div>Paiement: {{ $document->payment_terms }} jours</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Parties -->
        <div class="parties">
            <div class="party">
                <div class="party-title">Émetteur</div>
                <div class="party-name">{{ $company->legal_name ?: $company->name }}</div>
                <div class="party-details">
                    {{ $company->address }}<br>
                    {{ $company->postal_code }} {{ $company->city }}
                </div>
            </div>
            <div class="party">
                <div class="party-title">Client</div>
                <div class="party-name">{{ $client->name }}</div>
                <div class="party-details">
                    @if($client->contact_name){{ $client->contact_name }}<br>@endif
                    {{ $client->address }}<br>
                    {{ $client->postal_code }} {{ $client->city }}<br>
                    @if($client->email){{ $client->email }}<br>@endif
                    @if($client->phone){{ $client->phone }}@endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Qté</th>
                    <th class="text-right">Prix Unit.</th>
                    <th class="text-right">TVA</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($document->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ $item->tax_rate }}%</td>
                    <td class="text-right">{{ number_format($item->total, 2, ',', ' ') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <span>Sous-total HT</span>
                <span>{{ number_format($document->subtotal, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
            </div>
            @if($document->discount_amount > 0)
            <div class="totals-row">
                <span>Remise</span>
                <span>- {{ number_format($document->discount_amount, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
            </div>
            @endif
            <div class="totals-row">
                <span>TVA</span>
                <span>{{ number_format($document->tax_amount, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
            </div>
            <div class="totals-row total">
                <span>Total TTC</span>
                <span>{{ number_format($document->total, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
            </div>
            @if($document->paid_amount > 0)
            <div class="totals-row">
                <span>Déjà payé</span>
                <span>- {{ number_format($document->paid_amount, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
            </div>
            <div class="totals-row total">
                <span>Reste à payer</span>
                <span>{{ number_format($document->balance, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
            </div>
            @endif
        </div>

        <!-- Notes & Terms -->
        @if($document->notes || $document->terms_conditions)
        <div class="notes-terms">
            @if($document->notes)
            <div class="notes-terms-title">Notes</div>
            <div class="notes-terms-content">{{ $document->notes }}</div>
            @endif
            @if($document->terms_conditions)
            <div class="notes-terms-title" style="margin-top: 15px;">Conditions de règlement</div>
            <div class="notes-terms-content">{{ $document->terms_conditions }}</div>
            @endif
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            @if($company->email){{ $company->email }} | @endif
            @if($company->phone){{ $company->phone }} | @endif
            @if($company->website){{ $company->website }}@endif
        </div>
    </div>
</body>
</html>

