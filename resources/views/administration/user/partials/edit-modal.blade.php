<div class="modal fade" id="edit-user-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-user-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name">Name:</label>
                        <input id="edit-name" type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-level">Level:</label>
                        <select name="level" id="edit-level" class="form-select">
                            <option value=""></option>
                            @foreach ($levels as $level)
                                <option value="{{ $level }}">{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email">Email:</label>
                        <input id="edit-email" type="email" class="form-control" name="email" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#edit-user-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $(this).attr('data-id');
            var url = "{{ route('user.update', ['user' => ':id']) }}".replace(':id', id);
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
                    $("#edit-user-modal").modal('hide');
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
                        content: 'Edit user failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
