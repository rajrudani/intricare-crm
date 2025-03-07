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
     * @param array $contactData
     * @return Contact
     */
    public function storeContact($contactData)
    {
        return Contact::create([
            'name'            => $contactData['name'],
            'email'           => $contactData['email'],
            'phone'           => $contactData['phone'],
            'gender'          => $contactData['gender'],
            'custom_fields'   => isset($contactData['custom_fields']) ? json_encode($contactData['custom_fields']) : null,
            'profile_image'   => isset($contactData['profile_image']) ? $contactData['profile_image']->store('profile_images', 'public') : null,
            'additional_file' => isset($contactData['additional_file']) ? $contactData['additional_file']->store('additional_files', 'public') : null,
        ]);
    }
}