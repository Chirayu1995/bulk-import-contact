<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;
use Livewire\WithPagination;

class ContactsTable extends Component
{
    use WithPagination;

    public $editingId = null;
    public $editingName = '';
    public $editingPhone = '';

    protected $listeners = ['refreshTable' => '$refresh'];

    public function editContact($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $this->editingId = $id;
            $this->editingName = $contact->name;
            $this->editingPhone = $contact->phone;
        }
    }

    public function saveContact($id)
    {
        $contact = Contact::find($id);
        
        if ($contact) {
            // Check if the phone has changed
            if ($this->editingPhone !== $contact->phone) {
                // Check if another contact with the same phone exists
                $exists = Contact::where('phone', $this->editingPhone)->exists();

                if ($exists) {
                    $this->editingId = null;
                    $this->dispatch('refreshTable');
                    $this->dispatch('warning', message: "Contact with this phone already exists! Duplicates not allowed.");
                    return;
                }
            }

            // Update the contact with the new name and/or phone
            $contact->update([
                'name' => $this->editingName,
                'phone' => $this->editingPhone,
            ]);

            $this->editingId = null;
            $this->dispatch('refreshTable');
            $this->dispatch('success', message: "Contact updated successfully!");
        }
    }

    public function cancelEdit()
    {
        $this->editingId = null; 
    }

    public function deleteContact($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $contact->delete();
            $this->dispatch('refreshTable');
            $this->dispatch('success', message: "Contact deleted successfully!");
        }
    }

    public function render()
    {
        return view('livewire.contacts-table', [
            'contacts' => Contact::all(),
        ]);
    }
}
