<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed'); // Refresh the database before each test
        $user = User::factory()->create(); // Create a user (or use an existing one)
        $this->actingAs($user); // Authenticate the user for the test
    }
    public function testIndex()
    {
        $this->artisan('migrate:fresh --seed');

        Contact::factory(3)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test Contact',
            'email' => 'test@example.com',
            'phone' => '123-456-7890',
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure(['id', 'name', 'email', 'phone', 'created_at', 'updated_at']);
        $this->assertDatabaseHas('contacts', $data);
    }


    public function testShow()
    {
        $contact = Contact::factory()->create();

        $response = $this->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment($contact->toArray());
    }

    public function testUpdate()
    {
        $contact = Contact::factory()->create();
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->patchJson("/api/contacts/{$contact->id}", $updatedData);

        $response->assertStatus(200);
        $response->assertJsonFragment($updatedData);
        $this->assertDatabaseHas('contacts', $updatedData);
    }

    public function testDestroy()
    {
        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }

    public function testImport()
    {
        $xmlData = '<contacts>
            <contact><name>John Doe</name><email>john.doe@example.com</email><phone>123-456-7890</phone></contact>
        </contacts>';

        $response = $this->post('/api/contacts/import', [], ['Content-Type' => 'application/xml'], $xmlData);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Contacts imported successfully']);
        $this->assertDatabaseHas('contacts', ['name' => 'John Doe']);
    }
}
