<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ContactRepository implements ContactRepositoryInterface
{
    /**
     * Get the contacts data for DataTable with optional filters.
     *
     * @param array $filters
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function getDatatable($filters)
    {
        $contacts = Contact::query();
        foreach (['gender', 'email', 'name'] as $filter) {
            if (!empty($filters[$filter])) {
                $value = $filters[$filter];
                $contacts->where($filter, is_string($value) ? 'like' : '=', is_string($value) ? "%$value%" : $value);
            }
        }

        return DataTables::of($contacts)
            ->addColumn('profile_image', fn($row) => $row->profile_imagepath)
            ->addColumn('action', fn($row) => view('contacts.action-buttons', compact('row')))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a new contact in the database.
     *
     * @param array $validatedData
     * @return Contact
     */
    public function storeContact($validatedData)
    {
        return Contact::create([
            'name'            => $validatedData['name'],
            'email'           => $validatedData['email'],
            'phone'           => $validatedData['phone'],
            'gender'          => $validatedData['gender'],
            'custom_fields'   => isset($validatedData['custom_fields']) ? json_encode($validatedData['custom_fields']) : null,
            'profile_image'   => isset($validatedData['profile_image']) ? $validatedData['profile_image']->store('profile_images', 'public') : null,
            'additional_file' => isset($validatedData['additional_file']) ? $validatedData['additional_file']->store('additional_files', 'public') : null,
        ]);
    }

    /**
     * Update exisiting contact in the database.
     *
     * @param array $validatedData
     * @param array $contact
     * @return Contact
     */
    public function updateContact($validatedData, $contact)
    {
        return $contact->update([
            'name'            => $validatedData['name'],
            'email'           => $validatedData['email'],
            'phone'           => $validatedData['phone'],
            'gender'          => $validatedData['gender'],
            'custom_fields'   => isset($validatedData['custom_fields']) ? json_encode($validatedData['custom_fields']) : null,
            'profile_image'   => isset($validatedData['profile_image']) ? $validatedData['profile_image']->store('profile_images', 'public') : $contact->profile_image,
            'additional_file' => isset($validatedData['additional_file']) ? $validatedData['additional_file']->store('additional_files', 'public') : $contact->additional_file,
        ]);
    }

    public function destroyContact($contact)
    {   
        return $contact->delete();
    }
}