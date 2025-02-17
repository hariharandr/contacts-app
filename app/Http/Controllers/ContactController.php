<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use Exception;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
        ]);

        Contact::create($request->all());

        return redirect()->route('contacts.index')->with('success', 'Contact created successfully!');
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
        ]);

        $contact->update($request->all());

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml',
        ]);

        try {
            $file = $request->file('xml_file');
            $path = $file->store('xml_uploads');
            $xmlData = file_get_contents(storage_path('app/' . $path));

            $importedContacts = $this->importFromXML($xmlData);

            return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully!');
        } catch (ValidationException $e) {
            Log::error("Import validation failed: " . $e->getMessage());
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error("Import failed: " . $e->getMessage());
            return back()->with('error', 'Failed to import contacts. Please check the logs for details.');
        }
    }

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
