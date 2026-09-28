<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\DocumentList;
use App\Livewire\DocumentForm;
use App\Livewire\DocumentShow;
use App\Livewire\ClientList;
use App\Livewire\ClientForm;
use App\Livewire\CompanySettings;
use App\Actions\GenerateDocumentPDF;

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Documents
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', DocumentList::class)->name('index');

        // Direct route to form (must be before /create/{type})
        Route::get('/form/{type}', DocumentForm::class)->name('form');

        // Create document (quote/invoice)
        Route::get('/create/{type}', function ($type) {
            if (!in_array($type, ['invoice', 'quote'])) {
                abort(404);
            }

            return to_route('documents.form', ['type' => $type]);
        })->name('create');

        Route::get('/{document}', DocumentShow::class)->name('show');
        Route::get('/{document}/edit', DocumentForm::class)->name('edit');

        // Convert quote to invoice
        Route::post('/{document}/convert', function ($document) {
            $doc = \App\Models\Document::findOrFail($document);

            if ($doc->type !== 'quote') {
                return back()->with('error', 'Seuls les devis peuvent être convertis en factures.');
            }

            if ($doc->hasBeenConverted()) {
                return back()->with('error', 'Ce devis a déjà été converti en facture.');
            }

            $invoice = $doc->convertToInvoice();

            return redirect()->route('documents.show', $invoice)->with('message', 'Facture créée avec succès à partir du devis.');
        })->name('convert');

        // PDF Generation - download
        Route::get('/{document}/pdf', function ($document) {
            $doc = \App\Models\Document::with(['company', 'client', 'items'])->findOrFail($document);
            $action = new GenerateDocumentPDF();
            $pdf = $action->generate($doc);

            return $pdf->download($doc->number . '.pdf');
        })->name('pdf');

        // PDF Generation - view inline
        Route::get('/{document}/view-pdf', function ($document) {
            $doc = \App\Models\Document::with(['company', 'client', 'items'])->findOrFail($document);
            $action = new GenerateDocumentPDF();
            $pdf = $action->generate($doc);
            $pdfContent = $pdf->output();

            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $doc->number . '.pdf"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ]);
        })->name('view-pdf');
    });

    // Clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', ClientList::class)->name('index');
        Route::get('/create', ClientForm::class)->name('create');
        Route::get('/{client}', ClientList::class)->name('show');
        Route::get('/{client}/edit', ClientForm::class)->name('edit');
    });

    // Company Settings
    Route::get('/settings', CompanySettings::class)->name('settings');
});

// Landing page (default)
Route::get('/', function () {
    return view('landing');
});

// Pages légales : /terms et /policy sont gérées par Jetstream (contenu français)
// (voir la section 'terms' / 'policy' de config/jetstream.php)
Route::redirect('/terms', '/terms-of-service');
Route::redirect('/policy', '/privacy-policy');

// Socialite authentication (Google OAuth)
Route::get('/auth/redirect/{provider}', [App\Http\Controllers\SocialiteController::class, 'redirect'])
    ->name('auth.redirect')
    ->where('provider', 'google');

Route::get('/auth/callback/{provider}', [App\Http\Controllers\SocialiteController::class, 'callback'])
    ->name('auth.callback')
    ->where('provider', 'google');



