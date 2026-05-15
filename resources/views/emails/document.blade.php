<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .email-wrapper {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
        }

        .email-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .email-header .company-name {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }

        .email-content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
        }

        .message {
            color: #4b5563;
            margin-bottom: 24px;
            white-space: pre-line;
        }

        .document-info {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .document-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .document-info-row:last-child {
            border-bottom: none;
        }

        .document-info-label {
            color: #6b7280;
            font-size: 14px;
        }

        .document-info-value {
            color: #111827;
            font-weight: 600;
            font-size: 14px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }

        .cta-button:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
        }

        .email-footer {
            background-color: #f9fafb;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text {
            color: #9ca3af;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .footer-contact {
            color: #6b7280;
            font-size: 12px;
        }

        @media only screen and (max-width: 640px) {
            .container {
                padding: 10px;
            }

            .email-header,
            .email-content,
            .email-footer {
                padding: 24px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="email-wrapper">
            <!-- Header -->
            <div class="email-header">
                <h1>{{ $document->type === 'quote' ? 'Devis' : 'Facture' }} {{ $document->number }}</h1>
                <p class="company-name">{{ $company->name }}</p>
            </div>

            <!-- Content -->
            <div class="email-content">
                <p class="greeting">Bonjour,</p>

                <div class="message">{{ $body }}</div>

                <!-- Document Summary -->
                <div class="document-info">
                    <div class="document-info-row">
                        <span class="document-info-label">Type</span>
                        <span class="document-info-value">{{ $document->type === 'quote' ? 'Devis' : 'Facture' }}</span>
                    </div>
                    <div class="document-info-row">
                        <span class="document-info-label">Numéro</span>
                        <span class="document-info-value">{{ $document->number }}</span>
                    </div>
                    <div class="document-info-row">
                        <span class="document-info-label">Date d'émission</span>
                        <span class="document-info-value">{{ $document->issue_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="document-info-row">
                        <span class="document-info-label">Montant</span>
                        <span class="document-info-value"
                            style="color: #4f46e5; font-size: 16px;">{{ $document->formatted_total }}</span>
                    </div>
                    @if($document->type === 'invoice')
                        <div class="document-info-row">
                            <span class="document-info-label">Échéance</span>
                            <span class="document-info-value">{{ $document->due_date->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>

                <!-- View Document Link -->
                <div style="text-align: center; margin-top: 24px;">
                    <a href="{{ route('documents.view-pdf', $document) }}" class="cta-button">
                        Voir le document
                    </a>
                </div>

                <p style="color: #9ca3af; font-size: 12px; text-align: center; margin-top: 24px;">
                    Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur:<br>
                    <span style="color: #4f46e5;">{{ route('documents.view-pdf', $document) }}</span>
                </p>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p class="footer-text">
                    Ce {{ $document->type === 'quote' ? 'devis' : 'document' }} a été envoyé par {{ $company->name }}
                </p>
                <p class="footer-contact">
                    @if($company->email){{ $company->email }}@endif
                    @if($company->phone) | {{ $company->phone }}@endif
                </p>
            </div>
        </div>
    </div>
</body>

</html>