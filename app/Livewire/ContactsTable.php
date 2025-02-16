<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;
use Livewire\WithPagination;

class ContactsTable extends Component
{
    use WithPagination;

    protected $listeners = ['refreshTable' => '$refresh'];

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
