<?php

namespace App\Services;

use App\Models\Contact;
use SimpleXMLElement;
use Exception;

/**
 * ContactService provides business logic for managing contacts.
 *
 * This service encapsulates the core operations related to contacts,
 * including retrieval, creation, updating, deletion, and import from XML.
 */
class ContactService
{
    /**
     * Retrieves all contacts from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Contact[]
     */
    public function getAllContacts()
    {
        return Contact::all();
    }

    /**
     * Creates a new contact in the database.
     *
     * @param  array  $data The contact data.
     * @return \App\Models\Contact The newly created contact.
     */
    public function createContact(array $data)
    {
        return Contact::create($data);
    }

    /**
     * Retrieves a specific contact from the database by ID.
     *
     * @param  int  $id The ID of the contact.
     * @return \App\Models\Contact|null The contact, or null if not found.
     */
    public function getContact($id)
    {
        return Contact::find($id);
    }

    /**
     * Updates an existing contact in the database.
     *
     * @param  \App\Models\Contact  $contact The contact to update.
     * @param  array  $data The updated contact data.
     * @return \App\Models\Contact The updated contact.
     */
    public function updateContact(Contact $contact, array $data)
    {
        $contact->update($data);
        return $contact;
    }

    /**
     * Deletes a contact from the database.
     *
     * @param  \App\Models\Contact  $contact The contact to delete.
     * @return bool True on successful deletion, false otherwise.
     */
    public function deleteContact(Contact $contact)
    {
        return $contact->delete();
    }

    /**
     * Imports contacts from XML data.
     *
     * @param  string  $xmlData The XML data to import.
     * @return array An array of imported Contact models.
     * @throws \Exception If there is an error parsing the XML.
     */
    public function importFromXML($xmlData)
    {
        try {
            $xml = new SimpleXMLElement($xmlData);
            $importedContacts = [];

            foreach ($xml->contact as $contact) {
                $newContact = new Contact();
                $newContact->name = (string)$contact->name;
                $newContact->email = (string)$contact->email;
                $newContact->phone = (string)$contact->phone;
                $newContact->save();
                $importedContacts[] = $newContact;
            }

            return $importedContacts;
        } catch (Exception $e) {
            throw new Exception("Error parsing XML: " . $e->getMessage());
        }
    }
}
