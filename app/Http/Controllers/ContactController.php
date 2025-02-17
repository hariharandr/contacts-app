<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\ContactService;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;

/**
 * ContactController handles CRUD operations and import functionality for contacts.
 */
class ContactController extends Controller
{
    protected $contactService;

    /**
     * Constructor.
     *
     * @param ContactService $contactService The contact service.
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of contacts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $contacts = Contact::all(); // Consider pagination for large datasets: Contact::paginate(10);
        return response()->json($contacts);
    }

    /**
     * Store a newly created contact in storage.
     *
     * @param  StoreContactRequest  $request The validated request data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreContactRequest $request)
    {
        $validatedData = $request->validated();
        $contact = $this->contactService->createContact($validatedData);
        return response()->json($contact, 201); // 201 Created
    }

    /**
     * Display the specified contact.
     *
     * @param  Contact $contact The contact model (route model binding).
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

    /**
     * Update the specified contact in storage.
     *
     * @param  UpdateContactRequest  $request The validated request data.
     * @param  Contact $contact The contact model (route model binding).
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $validatedData = $request->validated();
        $updatedContact = $this->contactService->updateContact($contact, $validatedData);
        return response()->json($updatedContact, 200);
    }

    /**
     * Remove the specified contact from storage.
     *
     * @param  Contact $contact The contact model (route model binding).
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(null, 204); // 204 No Content
    }

    /**
     * Import contacts from XML data.
     *
     * @param  \Illuminate\Http\Request  $request The request containing the XML data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request)
    {
        try {
            $xmlData = $request->getContent();
            $importedContacts = $this->contactService->importFromXML($xmlData);
            return response()->json(['message' => 'Contacts imported successfully', 'contacts' => $importedContacts], 200);
        } catch (ValidationException $e) {
            Log::error("Import validation failed: " . $e->getMessage());
            return response()->json(['errors' => $e->errors()], 422); // 422 Unprocessable Entity

        } catch (\Exception $e) {
            Log::error("Import failed: " . $e->getMessage());

            $statusCode = $e->getCode() ?: 500; // Get the error code or default to 500
            $errorMessage = $e->getMessage() ?: 'Failed to import contacts. Please check the logs for details.';

            return response()->json(['error' => $errorMessage], $statusCode);
        }
    }
}
