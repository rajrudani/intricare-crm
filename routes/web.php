<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return redirect()->route('contacts.index');
});

Route::resource('contacts', ContactController::class);

Route::prefix('contacts')->as('contacts.')->group(function () {
    Route::get('merge-contact-modal/{contactId}', [ContactController::class, 'getMergeContactModal'])->name('merge-contact-modal');
    Route::post('merge-contacts', [ContactController::class, 'mergeContacts'])->name('merge-contacts');
});
