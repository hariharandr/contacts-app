<?php

namespace Tests\Unit\Services;

use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContactServiceTest extends TestCase
{
    use DatabaseTransactions;

    private ContactService $contactService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contactService = new ContactService();
    }

    public function testCreateContact()
    {
        $data = [
            'name' => 'Test Contact',
            'email' => 'test@example.com',
            'phone' => '123-456-7890',
        ];

        $contact = $this->contactService->createContact($data);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals($data['name'], $contact->name);
        $this->assertDatabaseHas('contacts', $data);
    }

    public function testUpdateContact()
    {
        $contact = Contact::factory()->create();
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $updatedContact = $this->contactService->updateContact($contact, $updatedData);

        $this->assertEquals($updatedData['name'], $updatedContact->name);
        $this->assertDatabaseHas('contacts', $updatedData);
    }

    public function testImportFromXML()
    {
        $xmlData = '<contacts>
            <contact><name>John Doe</name><email>john.doe@example.com</email><phone>123-456-7890</phone></contact>
            <contact><name>Jane Doe</name><email>jane.doe@example.com</email><phone>987-654-3210</phone></contact>
        </contacts>';

        $importedContacts = $this->contactService->importFromXML($xmlData);

        $this->assertCount(2, $importedContacts);
        $this->assertDatabaseHas('contacts', ['name' => 'John Doe']);
        $this->assertDatabaseHas('contacts', ['name' => 'Jane Doe']);
    }
}
