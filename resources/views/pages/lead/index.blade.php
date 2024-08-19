@extends('layout')
@section('content')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-lead-modal">
        Add Lead
    </button>
    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter-lead-modal">
        Filter
    </button>

    <div style="overflow-y: hidden">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    @include('pages.lead.partials.create-modal')
    @include('pages.lead.partials.edit-modal')
    @include('pages.lead.partials.show-modal')
    @include('pages.lead.partials.filter-modal')
@endsection

@push('scripts')
    <script>
        var dt = null;
        let queryString = window.location.href;

        // Datatable definition
        dt = $('.datatable').DataTable({
            ajax: {
                url: queryString,
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
                    width: 20,
                    render: (data, type, row, meta) => {
                        return meta.row + 1;
                    },
                    orderable: true,
                },
                {
                    data: 'name',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
                },
                {
                    data: 'contact',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    }
                },
                {
                    data: 'created_at',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },

                },
                {
                    data: 'updated_at',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
                },
                {
                    data: 'id',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
                    render: (data, type, row, meta) => {
                        const btn_edit =
                            `<button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#edit-lead-modal" data-id=":id">
                                    <i class="bi bi-pencil"></i>
                                </button>`.replace(':id', row.id);
                        const btn_show =
                            `<button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#show-lead-modal" data-id=":id">
                                <i class="bi bi-eye"></i>
                            </button>`.replace(':id', row.id);
                        const btn_delete =
                            `<form action="" class="d-inline delete-lead-form" data-id=":id">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn icon btn-danger">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>`.replace(':id', row.id);
                        return `${btn_edit} ${btn_show} ${btn_delete}`;
                    },
                },
            ]
        });

        // MODAL EDIT lead SHOWN
        $('#edit-lead-modal').on('shown.bs.modal', (e) => {
            var id = $(e.relatedTarget).data('id');
            var url = "{{ route('lead.edit', ['lead' => ':id']) }}".replace(':id', id);
            $('#edit-lead-form').attr('data-id', id); //set form's data-id

            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    $('#edit-lead-form [id="edit-name"]').val(response.name);
                    $('#edit-lead-form [id="edit-contact"]').val(response.contact);
                },
                error: function(response) {
                    showToast({
                        content: 'server error',
                        type: 'error'
                    });
                }
            });
        });

        // MODAL SHOW lead SHOWN
        $('#show-lead-modal').on('shown.bs.modal', (e) => {
            var id = $(e.relatedTarget).data('id');
            var url = "{{ route('lead.show', ['lead' => ':id']) }}".replace(':id', id);
            $('#show-lead-form').attr('data-id', id); //set form's data-id

            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    $('#show-lead-form [id="show-name"]').val(response[0].lead_name);
                    $('#show-lead-form [id="show-contact"]').val(response[0].contact);
                    populateTable(response, '#show-product-table tbody');
                },
                error: function(response) {
                    showToast({
                        content: 'server error',
                        type: 'error'
                    });
                }
            });
        });

        function populateTable(details, tbodySelector) {
            const table = $(tbodySelector);
            table.empty(); // Clear existing data

            // Build the entire table structure as a string
            let tableContent = ``;

            details.forEach(detail => {
                tableContent += `
                <tr>
                    <td>${detail['product_name']}</td>
                    <td>${detail['quantity']}</td>
                </tr>`;
            });

            // Append the complete table structure to the table element
            table.append(tableContent);
        }

        // DELETE lead SUBMITTED
        // HARUS EVENT DELEGATION
        $(document).on('submit', '.delete-lead-form', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var formData = new FormData(this);
            var url = "{{ route('lead.destroy', ['lead' => ':id']) }}".replace(':id', id);

            confirmationModal().then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: (data) => {
                            showToast(data);
                            dt.ajax.reload(null, false);
                        },
                        error: function(data) {
                            showToast({
                                content: 'delete lead failed',
                                type: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
