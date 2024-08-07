<div class="modal fade" id="edit-product-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-product-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name">product Name:</label>
                        <input id="edit-name" type="text" placeholder="product Name" class="form-control"
                            name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-desc">Desc:</label>
                        <input id="edit-desc" type="text" placeholder="desc" class="form-control" name="desc"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-price">Price:</label>
                        <input id="edit-price" type="number" placeholder="price" class="form-control" name="price"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#edit-product-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $(this).data('id');
            var url = "{{ route('product.update', ['product' => ':id']) }}".replace(':id', id);
            var formElement = $(this);
            removeErrorMessages(formElement);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    showToast(data);
                    $("#edit-product-modal").modal('hide');
                    dt.ajax.reload(null, false); // reload datatable
                },
                error: function(xhr) {
                    // error laravel validation
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessages(errors, formElement, 'edit');
                    } else {
                        swal("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'Edit product failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
