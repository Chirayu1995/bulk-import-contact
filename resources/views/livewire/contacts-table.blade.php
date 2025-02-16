<div>
<!-- Data Table -->
    <div class="card">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
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
                        <td class="text-center">{{$contact->name}}</td>
                        <td class="text-center">{{$contact->phone}}</td>
                        <td class="text-center">{{date('d F, Y h:i A',strtotime($contact->created_at))}}</td>
                        <td class="text-center">
                            <button wire:click="editContact({{ $contact->id }})" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button wire:click="deleteContact({{ $contact->id }})" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
