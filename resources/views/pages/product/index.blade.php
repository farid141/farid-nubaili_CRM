@extends('layout')
@section('content')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-product-modal">
        Add product
    </button>

    <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Desc</th>
                <th>Price</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    @include('pages.product.partials.create-modal')
    @include('pages.product.partials.edit-modal')
@endsection

@push('scripts')
    <script>
        var dt = null;
        // Datatable definition
        dt = $('.datatable').DataTable({
            ajax: {
                url: '{!! route('product.index') !!}',
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
                    data: 'desc'
                },
                {
                    data: 'price'
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
                                    data-bs-target="#edit-product-modal" data-id=":id">
                                    <i class="bi bi-pencil"></i>
                                </button>`.replace(':id', row.id);
                        const btn_delete =
                            `<form action="" class="d-inline delete-product-form" data-id=":id">
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

        // MODAL EDIT product SHOWN
        $('#edit-product-modal').on('shown.bs.modal', (e) => {
            var id = $(e.relatedTarget).data('id');
            var url = "{{ route('product.edit', ['product' => ':id']) }}".replace(':id', id);
            $('#edit-product-form').attr('data-id', id); //set form's data-id

            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    $('#edit-product-form [id="edit-name"]').val(response.name);
                    $('#edit-product-form [id="edit-desc"]').val(response.desc);
                    $('#edit-product-form [id="edit-price"]').val(response.price);
                },
                error: function(response) {
                    showToast({
                        content: 'server error',
                        type: 'error'
                    });
                }
            });
        });

        // DELETE product SUBMITTED
        // HARUS EVENT DELEGATION
        $(document).on('submit', '.delete-product-form', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var formData = new FormData(this);
            var url = "{{ route('product.destroy', ['product' => ':id']) }}".replace(':id', id);

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
                                content: 'delete product failed',
                                type: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
