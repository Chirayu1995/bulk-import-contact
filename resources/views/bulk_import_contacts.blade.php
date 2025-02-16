<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Import XML Contacts</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="container mt-5">
        <!-- Heading -->
        <h1 class="text-center mb-4">Bulk Import XML Contacts</h1>

        <!-- File Upload Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="uploadForm" action="{{ route('contacts.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 me-3">
                            <label for="fileInput" class="form-label">Choose Contacts XML File</label>
                            <input class="form-control" type="file" name="file" id="fileInput" accept=".xml" required>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary align-self-start mt-4">
                                <i class="fas fa-upload"></i> Upload and Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        
            {{-- Success Message --}}
            @if(session('success'))
            <div class="card-body mb-4">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

            {{-- Display Validation Errors --}}
            @if ($errors->any())
            <div class="card-body mb-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="ps3">Errors in XML file</h5>
                    <hr>
                    @foreach ($errors->all() as $error)
                        @foreach (explode('.', $error) as $msg)
                            @if (!empty(trim($msg)))
                                <li>{{ trim($msg) }}</li><br>
                            @endif
                        @endforeach
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

        <!-- Data Table -->
        <div class="card">
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
                                <button class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm">
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

    <!-- Bootstrap 5 JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#contactsTable').DataTable({
                pageLength: 10,
            });

            // Handle form submission
            // $('#uploadForm').on('submit', function(event) {
            //     event.preventDefault();
            //     const fileInput = document.getElementById('fileInput');
            //     if (fileInput.files.length > 0) {
            //         const file = fileInput.files[0];
            //         alert(`File "${file.name}" uploaded successfully!`);
            //         // Add logic to process the file and update the table
            //     } else {
            //         alert('Please select a file to upload.');
            //     }
            // });
        });
    </script>
</body>
</html>