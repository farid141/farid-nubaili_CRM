<div class="modal fade" id="create-project-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Project</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-project-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3" hidden>
                        <label for="create-sales_id">sales_id:</label>
                        <input id="create-sales_id" type="text" class="form-control" name="sales_id"
                            value="{{ auth()->user()->id }}">
                    </div>
                    <div class="mb-3">
                        <label for="create-name">Name:</label>
                        <input id="create-name" type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-status">Status:</label>
                        <select id="edit-status" type="text" class="form-control" name="status" required>
                            <option value=""></option>
                            @foreach ($status as $state)
                                <option value="{{ $state }}">{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create-desc">Desc:</label>
                        <input id="create-desc" type="text" class="form-control" name="desc" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-manager_id">Manager:</label>
                        <select id="create-manager_id" type="text" class="form-control" name="manager_id" required>
                            <option value=""></option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create-lead_id">Lead:</label>
                        <select id="create-lead_id" type="text" class="form-control" name="lead_id" required>
                            <option value=""></option>
                            @foreach ($leads as $lead)
                                <option value="{{ $lead->id }}">{{ $lead->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create-start_date">Start Date:</label>
                        <input id="create-start_date" type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-total">Total:</label>
                        <input id="create-total" type="number" class="form-control" name="total" required>
                    </div>
                    <div class="mb-3 details-table" id="create-details-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Desc</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-primary add-detail">Add Detail</button>
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
        $('#create-project-form').submit(function(e) {
            e.preventDefault();
            var oldFormData = new FormData(this);
            var formData = new FormData();
            oldFormData.forEach(function(value, key) {
                // Exclude elements with names ending in []
                if (!key.endsWith('[]')) {
                    formData.append(key, value);
                }
            });
            var formElement = $(this);
            removeErrorMessages(formElement);

            // Build details array
            const details = [];
            $('#create-details-table tbody tr').each(function() {
                const row = $(this);
                const detail = {
                    id: row.find('input[name="id[]"]').val(),
                    product_id: row.find('select[name="product_id[]"]').val(),
                    desc: row.find('input[name="desc[]"]').val(),
                    price: row.find('input[name="price[]"]').val(),
                    quantity: row.find('input[name="quantity[]"]').val(),
                    subtotal: row.find('input[name="subtotal[]"]').val(),
                };
                details.push(detail);
            });

            formData.append('details', JSON.stringify(details));

            $.ajax({
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    showToast(data);
                    $("#create-project-modal").modal('hide');
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
                        content: 'Create project failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
