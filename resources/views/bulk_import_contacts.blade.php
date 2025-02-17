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
     <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>
    <div class="container mt-5">
        <!-- Heading -->
        <h1 class="text-center mb-4">Bulk Import XML Contacts</h1>

        <!-- File Upload Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="uploadForm" action="{{ route('contacts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 me-3">
                            <label for="fileInput" class="form-label">Choose Contacts XML File</label>
                            <input class="form-control" type="file" name="file" id="fileInput" accept=".xml" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary align-self-start mt-4">
                                <i class="fas fa-upload"></i> Upload and Submit
                            </button>
                            <a href="{{ asset('contacts.xml') }}" class="btn btn-success align-self-start mt-4" download>
                                <i class="fas fa-download"></i> Download XML Sample
                            </a>
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
            <livewire:contacts-table />
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts 
    <!-- Bootstrap 5 JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function() {
        // Function to initialize DataTable
        function initDataTable() {
            if ($.fn.DataTable.isDataTable("#contactsTable")) {
                $("#contactsTable").DataTable().destroy();
            }
            $("#contactsTable").DataTable({
                pageLength: 10,
                responsive: true
            });
        }

        initDataTable();

        // Reinitialize DataTable after Livewire updates
        Livewire.on("refreshTable", function() {
            setTimeout(() => { 
                initDataTable(); 
            }, 100);
        });
    });
</script>
</body>
</html>