<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\ContactMerge;
use App\Models\ArchiveContact;
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
        foreach (['gender', 'email', 'name', 'visibility'] as $filter) {
            if (!empty($filters[$filter])) {
                $value = $filters[$filter];
                if($filter == 'gender'){
                    $contacts->where($filter, $value);
                } else if($filter == 'visibility'){
                    if($value == 'merged') $contacts->merged();
                    else if($value == 'not_merged') $contacts->notMerged();
                } else{
                    $contacts->where($filter, is_string($value) ? 'like' : '=', is_string($value) ? "%$value%" : $value);
                }
            }
        }
 
        return DataTables::of($contacts)
            ->addColumn('profile_image', fn($row) => $row->profile_imagepath)
            ->addColumn('action', fn($row) => view('contacts.partials.action-buttons', compact('row')))
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
        $mergedData = [
            'emails' => isset($validatedData['merged_emails']) ? array_unique($validatedData['merged_emails']) : [],
            'phones' => isset($validatedData['merged_phones']) ? array_unique($validatedData['merged_phones']) : [],
        ];

        return $contact->update([
            'name'            => $validatedData['name'],
            'email'           => $validatedData['email'],
            'phone'           => $validatedData['phone'],
            'gender'          => $validatedData['gender'],
            'merged_data'     => json_encode($mergedData),
            'custom_fields'   => isset($validatedData['custom_fields']) ? json_encode($validatedData['custom_fields']) : null,
            'profile_image'   => isset($validatedData['profile_image']) ? $validatedData['profile_image']->store('profile_images', 'public') : $contact->profile_image,
            'additional_file' => isset($validatedData['additional_file']) ? $validatedData['additional_file']->store('additional_files', 'public') : $contact->additional_file,
        ]);
    }

    /**
     * Delete an existing contact from the database.
     *
     * @param Contact $contact The contact instance to be deleted.
     * @return bool True if deleted successfully, false otherwise.
     */
    public function destroyContact($contact)
    {   
        return $contact->delete();
    }

    /**
     * Merge two contacts.
     */
    public function mergeContacts($validatedData)
    {   
        $masterContact = Contact::findOrFail($validatedData['master_contact_id']);
        $secondaryContact = Contact::findOrFail($validatedData['contact_id']);

        //archice master contact
        $this->archiveContact($masterContact);

        $masterContact->update([
            'merged_data'      => $this->mergeSecondaryData($masterContact, $secondaryContact),
            'custom_fields'   => $this->mergeCustomFields($masterContact->custom_fields, $secondaryContact->custom_fields),
            'additional_file' => $masterContact->additional_file ?? $secondaryContact->additional_file
        ]);

        $secondaryContact->update(['merged_with' => $masterContact->id]);

        return ContactMerge::create([
            'master_contact_id' => $masterContact->id,
            'merged_contact_id' => $secondaryContact->id,
        ]);
    }

    /**
     * archive contact to backup original data.
     */
    private function archiveContact($contact)
    {
        return ArchiveContact::create([
            'contact_id'      => $contact->id,
            'name'            => $contact->name,
            'gender'          => $contact->gender,
            'email'           => $contact->email,
            'phone'           => $contact->phone,
            'profile_image'   => $contact->profile_image,
            'additional_file' => $contact->additional_file,
            'custom_fields'   => $contact->custom_fields
        ]);
    }

    /**
     * Merge custom fields.
     */
    private function mergeCustomFields($masterFields, $secondaryFields)
    {
        $masterFieldMap = [];
        $mergedCustomFields = [];
        $masterCustomFields = json_decode($masterFields, true) ?? [];
        $secondaryCustomFields = json_decode($secondaryFields, true) ?? [];

        foreach ($masterCustomFields as $field) {
            $masterFieldMap[$field['title']] = [
                'value' => $field['value'],
                'action' => isset($field['action']) ? $field['action'] : 'original'
            ];
        }

        foreach ($secondaryCustomFields as $field) {
            $title = $field['title'];
            $value = $field['value'];

            if (!isset($masterFieldMap[$title])) {
                $masterFieldMap[$title] = [
                    'value' => $value,
                    'action' => 'merged'
                ];
            }
        }

        foreach ($masterFieldMap as $title => $data) {
            $mergedCustomFields[] = [
                'title' => $title,
                'value' => $data['value'],
                'action' => $data['action']
            ];
        }

        return json_encode($mergedCustomFields);
    }

    /**
     * Merge secondary contact emails and phone numbers into merged_data JSON column,
     * but only if they are not already in the master contact's primary email or phone.
     *
     * @param Contact $masterContact
     * @param Contact $secondaryContact
     * @return string (JSON)
     */
    private function mergeSecondaryData($masterContact, $secondaryContact)
    {
        $mergedData = json_decode($masterContact->merged_data, true) ?? [
            'emails' => [],
            'phones' => []
        ];

        $secondaryMergedData = json_decode($secondaryContact->merged_data, true) ?? [
            'emails' => [],
            'phones' => []
        ];

        $mergedData['emails'] = array_unique(
            array_merge(
                $mergedData['emails'],
                array_diff(
                    array_map('trim', explode(',', $secondaryContact->email)),
                    array_map('trim', explode(',', $masterContact->email))
                ),
                $secondaryMergedData['emails']
            )
        );
    
        $mergedData['phones'] = array_unique(
            array_merge(
                $mergedData['phones'],
                array_diff(
                    array_map('trim', explode(',', $secondaryContact->phone)),
                    array_map('trim', explode(',', $masterContact->phone))
                ),
                $secondaryMergedData['phones']
            )
        );

        return json_encode($mergedData);
    }
}