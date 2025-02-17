<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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

    public function importFromXML(string $xmlData)
    {
        try {
            $xml = simplexml_load_string($xmlData, null, LIBXML_NOBLANKS); // Load XML

            if ($xml === false) {
                throw new \Exception('Invalid XML format.');
            }

            $contacts = [];
            $errors = [];

            foreach ($xml->contact as $contact) {
                $data = [
                    'name' => (string)$contact->name,
                    'email' => (string)$contact->email ?? null, // Handle optional fields
                    'phone' => (string)$contact->phone,
                ];

                // Validate each contact's data
                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'email' => 'nullable|email|max:255',
                    'phone' => 'nullable|string|max:20',
                ]);

                if ($validator->fails()) {
                    Log::error("Validation error for contact: " . $validator->errors());
                    $errors[] = $validator->errors()->toArray(); // Add errors to the array
                    continue; // Skip to the next contact
                }

                $contacts[] = Contact::create($data);
            }

            return $contacts;
        } catch (\Exception $e) {
            Log::error("XML import error: " . $e->getMessage()); // Log the error
            throw $e; // Re-throw the exception to be handled by the controller
        }
    }
}
