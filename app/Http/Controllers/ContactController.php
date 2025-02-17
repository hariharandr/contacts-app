<?php

namespace App\Http\Controllers;

use App\Services\ContactService;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        $contacts = $this->contactService->getAllContacts();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(StoreContactRequest $request) // Use the request class here
    {
        $validatedData = $request->validated(); // Get validated data
        $this->contactService->createContact($validatedData);
        return redirect()->route('contacts.index');
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(UpdateContactRequest $request, Contact $contact) // Use the request class here
    {
        $validatedData = $request->validated(); // Get validated data
        $this->contactService->updateContact($contact, $validatedData);
        return redirect()->route('contacts.index');
    }

    public function destroy(Contact $contact)
    {
        $this->contactService->deleteContact($contact);
        return redirect()->route('contacts.index');
    }
}
