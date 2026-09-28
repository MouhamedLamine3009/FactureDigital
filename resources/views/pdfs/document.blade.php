<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $isQuote ? 'Devis' : 'Facture' }} {{ $document->number }}</title>
    @php
        $primary = $palette['primary'];
        $accent = $palette['accent'];
        $ink = $palette['ink'];
        $muted = $palette['muted'];
        $line = $palette['line'];
    @endphp
    <style>
        @page { margin: 0 20mm 16mm 20mm; size: A4 portrait; }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans";
            background: #ffffff;
            color: {{ $ink }};
            font-size: 9.5px;
        }

        table { border-collapse: collapse; border-spacing: 0; }

        /* ------------------------------------------------------------------
           Pied de page : `position:fixed` => DomPDF le reproduit sur CHAQUE
           page. La marge basse de @page (16mm) réserve la bande basse sur
           toutes les pages, donc le contenu ne peut jamais le chevaucher.
           NB : DomPDF ancre `left` sur la boite de contenu de @page (qui
           exclut deja la marge) -> il faut `left:0`, pas `left:20mm`.
        ------------------------------------------------------------------ */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
        .footer-text {
            border-top: 0.5pt solid {{ $line }};
            padding: 1.6mm 4mm 0 4mm;
            font-size: 7px;
            line-height: 1.35;
            color: {{ $muted }};
            text-align: center;
        }

        /* ------------------------------------------------------------------
           Bandeau : tableau 1x1 dont la cellule porte la couleur de fond et
           le padding -> la hauteur suit le contenu, aucune coupe.
        ------------------------------------------------------------------ */
        .band { width: 100%; }
        .band-cell { background: {{ $primary }}; padding: 6mm 7mm 5.5mm 7mm; }
        .brand-cell { width: 56%; vertical-align: middle; }
        .title-cell { width: 44%; vertical-align: middle; text-align: right; }

        .logo-cell { width: 15mm; padding-right: 4mm; vertical-align: middle; }
        .logo-img { display: block; width: 11mm; max-height: 11mm; }
        .logo-init {
            width: 11mm;
            height: 11mm;
            background: #ffffff;
            color: {{ $primary }};
            font-size: 15pt;
            font-weight: bold;
            text-align: center;
            line-height: 11mm;
        }
        .logo-init-2 { font-size: 9.5pt; letter-spacing: 0.5px; }
        .company-name {
            font-size: 11pt;
            font-weight: bold;
            line-height: 1.25;
            color: #ffffff;
        }

        .doc-title {
            font-size: 25pt;
            font-weight: bold;
            line-height: 1.15;
            letter-spacing: 1px;
            color: #ffffff;
        }
        .doc-line { font-size: 9pt; line-height: 1.55; color: #ffffff; text-align: right; }
        .doc-key { color: #bfdbfe; font-weight: bold; margin-right: 1.5mm; }

        /* ------------------------------------------------------------------
           Émetteur / Client : deux colonnes, 50% + 50% = 100%
        ------------------------------------------------------------------ */
        .parties { width: 100%; margin-top: 6mm; }
        .party { width: 50%; vertical-align: top; padding-top: 4mm; border-top: 1.5pt solid {{ $accent }}; }
        .party-left { padding-right: 5mm; }
        .party-right { padding-left: 5mm; }
        .block-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: {{ $accent }};
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 1mm;
        }
        .block-name { font-size: 10pt; font-weight: bold; line-height: 1.3; padding-bottom: 0.8mm; }
        .block-line { font-size: 8pt; line-height: 1.5; color: {{ $muted }}; }
        .block-empty { font-size: 8pt; line-height: 1.5; color: #9aa3b0; font-style: italic; }

        /* ------------------------------------------------------------------
           Lignes de la facture
        ------------------------------------------------------------------ */
        table.items { width: 100%; margin-top: 5mm; table-layout: fixed; }
        .items thead { display: table-header-group; }
        .items th {
            background: {{ $accent }};
            color: #ffffff;
            font-size: 8.5pt;
            font-weight: bold;
            text-align: left;
            padding: 2mm;
        }
        .items td { padding: 1.6mm 2mm; line-height: 1.3; vertical-align: top; }
        .items tbody tr { page-break-inside: avoid; }
        .items tbody tr.alt td { background: #f4f5f7; }
        .center { text-align: center; }
        .right { text-align: right; }

        /* ------------------------------------------------------------------
           Totaux + informations
        ------------------------------------------------------------------ */
        table.lower { width: 100%; margin-top: 5mm; page-break-inside: avoid; }
        .left-info { width: 58%; vertical-align: top; padding-right: 5mm; }
        .totals { width: 42%; vertical-align: top; }
        .info-title { font-size: 8.5pt; font-weight: bold; color: {{ $accent }}; margin: 0 0 1mm 0; }
        .info-title-gap { margin-top: 3.5mm; }
        .info-text { font-size: 7.5pt; line-height: 1.45; color: {{ $muted }}; }
        .total-row { width: 100%; font-size: 9pt; line-height: 1.6; }
        .total-row td { padding: 0; }
        .total-label { text-align: right; padding-right: 2mm !important; color: {{ $muted }}; }
        .total-value { text-align: right; font-weight: bold; }
        .grand { background: {{ $accent }}; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; padding: 2mm; margin-top: 2.5mm; }
        .settled { background: #ecfdf5; color: #065f46; font-weight: bold; font-size: 9pt; text-align: center; padding: 1.6mm; margin-top: 2.5mm; }

        /* Signature devis : bloc normal dans le flux (plus d'absolute) */
        table.signature { width: 100%; margin-top: 9mm; page-break-inside: avoid; }
        .signature-cell { width: 62mm; vertical-align: top; text-align: center; }
        .signature-text { font-size: 8pt; line-height: 1.4; color: {{ $muted }}; }
        .signature-img { max-height: 18mm; max-width: 55mm; margin-bottom: 3mm; }
        .signature-line { border-bottom: 0.5pt solid {{ $muted }}; margin-top: 14mm; }
    </style>
</head>
<body>

<div class="page">
    {{-- ================= BANDEAU (1re page uniquement) ================= --}}
    <table class="band">
        <tr><td class="band-cell">
            <table style="width:100%">
                <tr>
                    <td class="brand-cell">
                        <table>
                            <tr>
                                <td class="logo-cell">
                                    @if($logoFile)
                                        <img class="logo-img" src="{{ $logoFile }}" alt="{{ $company->name }}">
                                    @else
                                        <div class="logo-init {{ strlen($companyInitials) > 1 ? 'logo-init-2' : '' }}">{{ $companyInitials }}</div>
                                    @endif
                                </td>
                                <td class="brand-text">
                                    <div class="company-name">{{ $companyLabel }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="title-cell">
                        <div class="doc-title">{{ $title }}</div>
                        <div class="doc-line"><span class="doc-key">N°</span>{{ $document->number }}</div>
                        <div class="doc-line"><span class="doc-key">Date</span>{{ $issueDate }}</div>
                        @if($dueDate)
                            <div class="doc-line"><span class="doc-key">{{ $isQuote ? 'Valable' : 'Échéance' }}</span>{{ $dueDate }}</div>
                        @endif
                    </td>
                </tr>
            </table>
        </td></tr>
    </table>

    {{-- ================= ÉMETTEUR / CLIENT ================= --}}
    <table class="parties">
        <tr>
            <td class="party party-left">
                <div class="block-title">Émetteur</div>
                @forelse($issuerLines as $line)
                    <div class="block-line">{{ $line }}</div>
                @empty
                    <div class="block-empty">Aucune coordonnée renseignée</div>
                @endforelse
            </td>
            <td class="party party-right">
                <div class="block-title">À l'attention de</div>
                <div class="block-name">{{ $client->name ?: 'Client' }}</div>
                @forelse($clientLines as $line)
                    <div class="block-line">{{ $line }}</div>
                @empty
                    <div class="block-empty">Aucune coordonnée renseignée</div>
                @endforelse
            </td>
        </tr>
    </table>

    {{-- ================= LIGNES ================= --}}
    <table class="items" style="font-size: {{ $itemFontSize }}pt">
        <thead>
            <tr>
                <th style="width:58%">Descriptif</th>
                <th class="center" style="width:17%">{{ $isQuote ? 'Nb. jours' : 'Qté' }}</th>
                <th class="right" style="width:25%">{{ $isQuote ? 'Tarif jour' : 'Prix unitaire' }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $index => $item)
            <tr class="{{ $index % 2 ? 'alt' : '' }}">
                <td>{{ $item->description }}</td>
                <td class="center">{{ rtrim(rtrim(number_format((float) $item->quantity, 2, ',', ''), '0'), ',') }}</td>
                <td class="right">{{ number_format((float) $item->unit_price, 0, ',', ' ') }} FCFA</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- ================= TOTAUX + INFORMATIONS ================= --}}
    <table class="lower">
        <tr>
            <td class="left-info">
                <div class="info-title">Informations de paiement</div>
                <div class="info-text">
                    @forelse($paymentLines as $line)
                        {{ $line }}<br>
                    @empty
                        Règlement à réception de facture.
                    @endforelse
                </div>
                <div class="info-title info-title-gap">Termes &amp; conditions</div>
                <div class="info-text">{{ $termsText }}</div>
            </td>
            <td class="totals">
                <table class="total-row">
                    <tr>
                        <td class="total-label">Sous-total</td>
                        <td class="total-value">{{ number_format((float) $document->subtotal, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @if((float) $document->discount_amount > 0)
                        <tr>
                            <td class="total-label">Remise</td>
                            <td class="total-value">− {{ number_format((float) $document->discount_amount, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="total-label">Taxes</td>
                        <td class="total-value">{{ number_format((float) $document->tax_amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </table>
                @if((float) $document->paid_amount > 0)
                    <div class="settled">Soldé —merci pour votre confiance</div>
                @endif
                <div class="grand">Total : {{ number_format((float) $document->total, 0, ',', ' ') }} FCFA</div>
                @if((float) $document->paid_amount > 0)
                    <div class="grand">Reste à payer : {{ number_format((float) $document->balance, 0, ',', ' ') }} FCFA</div>
                @endif
            </td>
        </tr>
    </table>

    @if($isQuote)
        <table class="signature">
            <tr><td class="signature-cell">
                @if($signatureFile)
                    <img class="signature-img" src="{{ $signatureFile }}" alt="Signature">
                @endif
                <div class="signature-text">Signature suivie de la<br>mention &laquo;&nbsp;bon pour accord&nbsp;&raquo;</div>
                <div class="signature-line"></div>
            </td></tr>
        </table>
    @endif
</div>

{{-- ================= PIED DE PAGE ================= --}}
@if($footerText !== '')
    <div class="footer">
        <table style="width:100%">
            <tr><td class="footer-text">{{ $footerText }}</td></tr>
        </table>
    </div>
@endif

</body>
</html>
