<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return redirect()->route('contacts.index');
});

Route::resource('contacts', ContactController::class);

Route::get('merge-contact-modal/{contactId}', [ContactController::class, 'getMergeContactModal'])->name('contacts.merge-contact-modal');
Route::post('merge-contacts', [ContactController::class, 'mergeContacts'])->name('contacts.merge-contacts');