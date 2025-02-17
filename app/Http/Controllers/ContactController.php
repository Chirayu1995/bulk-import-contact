<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all(); 
        return view('bulk_import_contacts', compact('contacts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xml|max:2048', // Only XML files, max 2MB
        ]);

        // Load the XML file
        $uploadedFile = file_get_contents($request->file('file'));
        libxml_use_internal_errors(true); // Enable error handling
        $xml = simplexml_load_string($uploadedFile);

        if (!$xml) {
            return back()->withErrors(['file' => 'Invalid XML file format.'])->withInput();
        }

        // Convert XML to an array
        $contacts = json_decode(json_encode($xml), true);

        // Ensure it has <contact> tags
        if (!isset($contacts['contact'])) {
            return back()->withErrors(['file' => 'Invalid XML: Missing <contact> tags.'])->withInput();
        }

        // Convert single contact to array if only one exists
        if (isset($contacts['contact']['phone'])) {
            $contacts['contact'] = [$contacts['contact']];
        }

        $errors = [];
        $newContacts = [];
        foreach ($contacts['contact'] as $contact) {
            // Validate phone number and name existence
            if ((empty($contact['phone']) || !isset($contact['phone'])) && (empty($contact['name']) || !isset($contact['name']))) {
                continue;
            }

            // Validate name existence
            if (!isset($contact['name']) || empty($contact['name'])) {
                $errors[] = "Phone number {$contact['phone']} has no associated name in the uploaded XML file.";
                continue;
            }

            // Validate phone number existence
            if (!isset($contact['phone']) || empty($contact['phone'])) {
                $errors[] = "Contact Name {$contact['name']} must have a phone number inside <phone> tag.";
                continue;
            }

            if (!preg_match('/^[\d\s+\-]+$/',$contact['phone'])) {
                $errors[] = "Contact name {$contact['name']} having contact number {$contact['phone']} is invalid, it can contain only space, -, + symbol and 0-9 digits in it.";
                continue;
            }

            // Check if the phone already exists in the database
            if (Contact::where('phone', $contact['phone'])->exists()) {
                $errors[] = "Phone number {$contact['phone']} already exists in the database.";
                continue;
            }

            // Prepare new contact data for bulk insertion
            $newContacts[] = [
                'name'  => $contact['name'] ?? null,
                'phone' => $contact['phone'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert new contacts
        $insertSuccess = false;
        if (!empty($newContacts)) {
            Contact::insert($newContacts);
            $insertSuccess = true;
        }

        // If there are errors, return them
        if (!empty($errors)) {
            if($insertSuccess)
            {
                return back()
                ->with('success', 'Contacts imported in bulk successfully.') // Show success message first
                ->withErrors(['file' => implode(' ', $errors)]) // Then show errors
                ->withInput(); // Keep the input data
            }
            return back()->withErrors(['file' => implode(' ', $errors)])->withInput();
        }
        
        return back()->with('success', 'Contacts imported in bulk successfully.');
    }
}
