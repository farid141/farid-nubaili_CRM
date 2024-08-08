<div class="modal fade" id="create-product-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-product-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Name:</label>
                        <input id="create-name" type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-desc">Desc:</label>
                        <input id="create-desc" type="text" class="form-control" name="desc" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-price">Price:</label>
                        <input id="create-price" type="number" class="form-control" name="price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#create-product-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var formElement = $(this);
            removeErrorMessages(formElement);

            $.ajax({
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    showToast(data);
                    $("#create-product-modal").modal('hide');
                    dt.ajax.reload(null, false); // refresh datatable
                },
                error: function(xhr) {
                    // error laravel validation
                    if (xhr.status == 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessages(errors, formElement, 'create');
                    } else {
                        swal("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'Create product failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
