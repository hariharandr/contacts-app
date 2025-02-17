<?php

namespace App\Services;

use App\Models\Contact;
use SimpleXMLElement;
use Exception;

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

    public function importFromXML($xmlData)
    {
        try {
            $xml = new SimpleXMLElement($xmlData);

            $importedContacts = [];

            foreach ($xml->contact as $contact) {
                $newContact = new Contact();
                $newContact->name = (string)$contact->name; // Cast to string
                $newContact->email = (string)$contact->email;
                $newContact->phone = (string)$contact->phone;
                $newContact->save();
                $importedContacts[] = $newContact;
            }

            return $importedContacts;
        } catch (Exception $e) {
            throw new Exception("Error parsing XML: " . $e->getMessage()); // Re-throw the exception
        }
    }
}
