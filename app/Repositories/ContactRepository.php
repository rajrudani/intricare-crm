<?php

namespace App\Repositories;

use App\Models\Contact;
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
            'email'           => $this->mergeEmails($masterContact->email, $secondaryContact->email),
            'phone'           => $this->mergePhoneNumbers($masterContact->phone, $secondaryContact->phone),
            'custom_fields'   => $this->mergeCustomFields($masterContact->custom_fields, $secondaryContact->custom_fields),
            'additional_file' => $masterContact->additional_file ?? $secondaryContact->additional_file
        ]);

        return $secondaryContact->update(['merged_with' => $masterContact->id]);
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
        $masterCustomFields = json_decode($masterFields, true) ?? [];
        $secondaryCustomFields = json_decode($secondaryFields, true) ?? [];

        $mergedCustomFields = [];

        $masterFieldMap = [];
        foreach ($masterCustomFields as $field) {
            $masterFieldMap[$field['title']] = $field['value'];
        }

        foreach ($secondaryCustomFields as $field) {
            $title = $field['title'];
            $value = $field['value'];

            if (!isset($masterFieldMap[$title])) {
                $masterFieldMap[$title] = $value;
            }
        }

        foreach ($masterFieldMap as $title => $value) {
            $mergedCustomFields[] = [
                'title' => $title,
                'value' => $value
            ];
        }

        return json_encode($mergedCustomFields);
    }

    /**
     * Merge emails, store additional emails if they differ.
     */
    private function mergeEmails($masterEmail, $secondaryEmail)
    {
        $mergedEmails = array_unique(
                            array_merge(
                                array_map('trim', explode(',', $masterEmail)), 
                                array_map('trim', explode(',', $secondaryEmail))
                            )
                        );

        return implode(',', $mergedEmails);
    }

    /**
     * Merge phone numbers, store additional phone numbers if they differ.
     */
    private function mergePhoneNumbers($masterPhone, $secondaryPhone)
    {
        $mergePhones = array_unique(
            array_merge(
                array_map('trim', explode(',', $masterPhone)), 
                array_map('trim', explode(',', $secondaryPhone))
            )
        );

        return implode(',', $mergePhones);
    }
}