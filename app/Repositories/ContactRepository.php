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

        if (isset($filters['gender'])) {
            $contacts->where('gender', $filters['gender']);
        }
        if (isset($filters['email'])) {
            $contacts->where('email', 'like', '%' . $filters['email'] . '%');
        }
        if (isset($filters['name'])) {
            $contacts->where('name', 'like', '%' . $filters['name'] . '%');
        }

        return DataTables::of($contacts)
            ->addColumn('profile_image', function ($row) {
                return $row->profile_imagepath;
            })
            ->addColumn('action', function ($row) {
                return view('contacts.action-buttons', compact('row'));
            })
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
        if ($contactData['profile_image']) {
            $profileImagePath = $contactData['profile_image']->store('profile_images', 'public');
        }

        if ($contactData['additional_file']) {
            $additionalFilePath = $contactData['additional_file']->store('additional_files', 'public');
        }

        return Contact::create([
            'name'            => $contactData['name'],
            'email'           => $contactData['email'],
            'phone'           => $contactData['phone'],
            'gender'          => $contactData['gender'],
            'custom_fields'   => $contactData['custom_fields'] ?? null,
            'profile_image'   => $profileImagePath ?? null,
            'additional_file' => $additionalFilePath ?? null,
        ]);
    }
}