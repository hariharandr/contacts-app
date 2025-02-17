<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use Exception;
use App\Services\ContactService;
use Illuminate\Support\Facades\Session;

/**
 * ContactController handles all operations related to contacts.
 *
 * This controller provides methods for displaying, creating, updating,
 * deleting, importing, and searching contacts.
 */
class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Displays a list of all contacts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $contacts = $this->contactService->getAllContacts();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Displays the form for creating a new contact.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Stores a newly created contact in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
        ]);

        Contact::create($request->all());

        return redirect()->route('contacts.index')->with('success', 'Contact created successfully!');
    }

    /**
     * Displays the form for editing an existing contact.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\View\View
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Updates the specified contact in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
        ]);

        $contact->update($request->all());

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully!');
    }

    /**
     * Removes the specified contact from the database.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully!');
    }

    /**
     * Imports contacts from an XML file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'xml_file' => 'required|file|mimes:xml',
            ]);
            $file = $request->file('xml_file');
            $path = $file->store('xml_uploads');
            $xmlData = file_get_contents(storage_path('app/' . $path));

            $importedContacts = $this->contactService->importFromXML($xmlData);

            // Success message with count
            Session::flash('success', count($importedContacts) . ' contacts imported successfully!');

            return redirect()->route('contacts.index');
        } catch (ValidationException $e) {
            // More specific error messages for validation
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    Session::flash('error', $message); // Flash each error message
                }
            }
            return back()->withInput();
        } catch (Exception $e) { // Catch general Exception
            Log::error("Import failed: " . $e->getMessage()); // Log the detailed error

            Session::flash('error', 'An error occurred during import. Please check the logs for details.');
            return back()->withInput();
        }
    }

    /**
     * Parses XML data and imports contacts into the database.
     *
     * @param  string  $xmlData
     * @return array
     * @throws \Exception
     */
    private function importFromXML($xmlData)
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

    /**
     * Searches for contacts based on the provided search term.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        if ($searchTerm) {
            $contacts = Contact::where('name', 'ilike', "%$searchTerm%")
                ->orWhere('email', 'ilike', "%$searchTerm%")
                ->orWhere('phone', 'ilike', "%$searchTerm%")
                ->get();
        } else {
            $contacts = Contact::all();
        }

        return view('contacts.index', compact('contacts'));
    }
}
