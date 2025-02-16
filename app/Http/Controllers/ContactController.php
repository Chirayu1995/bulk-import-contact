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
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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
            // Validate phone number existence
            if (!isset($contact['phone']) || empty($contact['phone'])) {
                $errors[] = "Each <contact> must have a <phone> tag.";
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

        // If there are errors, return them
        if (!empty($errors)) {
            return back()->withErrors(['file' => implode(' ', $errors)])->withInput();
        }

        // Insert new contacts
        if (!empty($newContacts)) {
            Contact::insert($newContacts);
        }

        return back()->with('success', 'Bulk contacts added successfully.'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
