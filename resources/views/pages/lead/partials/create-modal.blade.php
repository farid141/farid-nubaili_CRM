<div class="modal fade" id="create-lead-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create lead</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-lead-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Lead Name:</label>
                        <input id="create-name" type="text" placeholder="lead Name" class="form-control"
                            name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-contact">Contact:</label>
                        <input id="create-contact" type="text" placeholder="lead contact" class="form-control"
                            name="contact" required>
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
        $('#create-lead-form').submit(function(e) {
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
                    $("#create-lead-modal").modal('hide');
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
                        content: 'Create lead failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
