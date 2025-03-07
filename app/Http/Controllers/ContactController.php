<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Repositories\ContactRepositoryInterface;

class ContactController extends Controller
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Show the list of contacts, with an datatable for AJAX requests.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->contactRepository->getDatatable(request('filters', []));
        }

        return view('contacts.index');
    }

    /**
     * Show the form for creating a new contact.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Handle store new contact.
     *
     * @param ContactRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ContactRequest $request)
    {
        $this->contactRepository->storeContact($request->validated());

        return response()->json(['message' => 'Contact saved successfully!'], 200);
    }

    /**
     * Show the form for editing a contact.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Handle update existing contact.
     *
     * @param ContactRequest $request
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ContactRequest $request, Contact $contact)
    {
        $this->contactRepository->updateContact($request->validated(), $contact);

        return response()->json(['message' => 'Contact Updated successfully!'], 200);
    }

    public function destroy(Contact $contact)
    {
        $this->contactRepository->destroyContact($contact);
       
        return response()->json(['message' => 'Contact deleted successfully.'], 200);
    }
}
