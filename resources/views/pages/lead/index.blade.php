@extends('layout')
@section('content')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-lead-modal">
        Add Lead
    </button>

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

    @include('pages.lead.partials.create-modal')
    @include('pages.lead.partials.edit-modal')
@endsection

@push('scripts')
    <script>
        var dt = null;
        // Datatable definition
        dt = $('.datatable').DataTable({
            ajax: {
                url: '{!! route('lead.index') !!}',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    width: 20,
                    render: (data, type, row, meta) => {
                        return meta.row + 1;
                    },
                    orderable: true,
                },
                {
                    data: 'name'
                },
                {
                    data: 'contact'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'id',
                    render: (data, type, row, meta) => {
                        const btn_edit =
                            `<button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#edit-lead-modal" data-id=":id">
                                    <i class="bi bi-pencil"></i>
                                </button>`.replace(':id', row.id);
                        const btn_delete =
                            `<form action="" class="d-inline delete-lead-form" data-id=":id">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn icon btn-danger">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>`.replace(':id', row.id);
                        return `${btn_edit} ${btn_delete}`;
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
