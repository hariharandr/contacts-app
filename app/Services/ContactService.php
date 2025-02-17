<?php

namespace App\Services;

use App\Models\Contact;

class ContactService
{
    public function getAllContacts()
    {
        return Contact::all();
    }

    public function createContact(array $data)
    {
        return Contact::create($data);
    }

    public function getContact($id)
    {
        return Contact::find($id);
    }

    public function updateContact(Contact $contact, array $data)
    {
        $contact->update($data);
        return $contact;
    }

    public function deleteContact(Contact $contact)
    {
        return $contact->delete();
    }
}
