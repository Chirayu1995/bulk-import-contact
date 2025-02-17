<div>
    <div class="card">
        <div x-data="{ open: false, message: '', type: '' }" 
            x-cloak 
            @success.window="open = true; message = $event.detail.message; type = 'success'; setTimeout(() => open = false, 3000)"
            @warning.window="open = true; message = $event.detail.message; type = 'warning'; setTimeout(() => open = false, 3000)">
            <div x-show="open" x-text="message" 
                :class="{
                    'alert alert-success': type === 'success',
                    'alert alert-danger': type === 'warning'
                }">
            </div>
        </div>
        <style>
            [x-cloak] { display: none !important; }
        </style>
        <div class="card-body">
            <table id="contactsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Created At</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($contacts ?? []) as $key => $contact) 
                    <tr>
                        <td class="text-center">{{++$key}}</td>

                        <td class="text-center">
                            @if($editingId === $contact->id)
                                <input type="text" wire:model="editingName" class="form-control">
                            @else
                                {{$contact->name}}
                            @endif
                        </td>

                        <td class="text-center">
                            @if($editingId === $contact->id)
                                <input type="text" wire:model="editingPhone" class="form-control" oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')">
                            @else
                                {{$contact->phone}}
                            @endif
                        </td>

                        <td class="text-center">{{date('d F, Y h:i A',strtotime($contact->created_at))}}</td>
                        
                        <td class="text-center">
                            @if($editingId === $contact->id)
                                <button wire:click="saveContact({{ $contact->id }})" class="btn btn-success btn-sm me-2">
                                    <i class="fas fa-check"></i> Save
                                </button>
                                <button wire:click="cancelEdit" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            @else
                                <button wire:click="editContact({{ $contact->id }})" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button wire:click="deleteContact({{ $contact->id }})" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
