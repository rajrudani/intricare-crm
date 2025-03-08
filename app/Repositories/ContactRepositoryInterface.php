<?php

namespace App\Repositories;

use App\Models\Contact;

interface ContactRepositoryInterface
{
    public function getDatatable(array $filters);

    public function storeContact(array $validatedData);

    public function updateContact(array $validatedData, Contact $contact);

    public function destroyContact(Contact $contact);

    public function mergeContacts(array $validatedData);
}
