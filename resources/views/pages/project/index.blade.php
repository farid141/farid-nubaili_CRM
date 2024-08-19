@extends('layout')
@section('content')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-project-modal">
        Add project
    </button>
    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter-project-modal">
        Filter
    </button>

    <div style="overflow-y: hidden">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Manager</th>
                    <th>Sales</th>
                    <th>Lead</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @include('pages.project.partials.create-modal')
    @include('pages.project.partials.edit-modal')
    @include('pages.project.partials.filter-modal')
@endsection

@push('scripts')
    <script>
        var products = @json($products);
        var managers = @json($managers);
        var leads = @json($leads);
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
                    data: 'start_date',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
                },
                {
                    data: 'total',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
                },
                {
                    data: 'status',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
                },
                {
                    data: 'manager.name',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
                },
                {
                    data: 'sales.name',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
                },
                {
                    data: 'lead.name',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-nowrap');
                    },
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
                                    data-bs-target="#edit-project-modal" data-id=":id">
                                    <i class="bi bi-pencil"></i>
                                </button>`.replace(':id', row.id);
                        const btn_download =
                            `<button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#show-project-modal" data-id=":id">
                                <i class="bi bi-eye"></i>
                            </button>`.replace(':id', row.id);
                        const btn_delete =
                            `<form action="" class="d-inline delete-project-form" data-id=":id">
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

        // MODAL EDIT project SHOWN
        $('#edit-project-modal').on('shown.bs.modal', (e) => {
            var id = $(e.relatedTarget).data('id');
            var url = "{{ route('project.edit', ['project' => ':id']) }}".replace(':id', id);
            $('#edit-project-form').attr('data-id', id); //set form's data-id

            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    $('#edit-name').val(response.name);
                    $('#edit-desc').val(response.desc);
                    $('#edit-total').val(response.total);
                    $('#edit-start_date').val(response.start_date);
                    $('#edit-sales_id').val(response.sales_id);
                    $(`#edit-status option[value="${response.status}"]`).prop('selected', true);
                    $(`#edit-manager_id option[value="${response.manager.id}"]`).prop('selected', true);
                    $(`#edit-lead_id option[value="${response.lead.id}"]`).prop('selected', true);
                    populateTable(response.details, '#edit-details-table tbody');
                },
                error: function(response) {
                    showToast({
                        content: 'server error',
                        type: 'error'
                    });
                }
            });
        });

        function populateTable(details, tableSelector) {
            const table = $(tableSelector);
            table.empty(); // Clear existing data

            // Build the entire table structure as a string
            let tableContent = ``;

            details.forEach(detail => {
                tableContent += '<tr>';
                tableContent += `<td hidden><input name="id[]" value="${detail['id']}"></input></td>`;
                tableContent += `<td>
                                    <select name="product_id[]" id="">`;
                products.forEach(product => {
                    const selected = detail['product_id'] === product['id'] ? 'selected' : '';
                    tableContent +=
                        `<option value="${product['id']}" ${selected}>${product['name']}</option>`;
                });
                tableContent += `</select>
                                </td>`;
                tableContent += `<td><input name="desc[]" value="${detail['desc']??''}"></input></td>`;
                tableContent +=
                    `<td><input type="number" name="price[]" disabled value="${detail['price']}"></input></td>`;
                tableContent +=
                    `<td><input type="number" name="quantity[]" value="${detail['quantity']}"></input></td>`;
                tableContent +=
                    `<td><input type="number" name="subtotal[]" disabled value="${detail['subtotal']}"></input></td>`;
                tableContent +=
                    `<td><button type="button" class="btn btn-danger delete-detail">Delete</button></td>`;
                tableContent += '</tr>';
            });

            // Append the complete table structure to the table element
            table.append(tableContent);
        }

        // delete row detail clicked
        $(document).on('click', '.delete-detail', function() {
            var uploadForm = $(this).closest('form'); //Get form selector before deleting DOM object
            $(this).closest('tr').remove();
            calculateTotal(uploadForm);
        });

        // add row detail clicked
        $('.add-detail').click(function() {
            var newRow = `
            <tr>
                <td>
                    <select name="product_id[]" id="">
                        <option value="" ></option>`;
            products.forEach(product => {
                newRow += `
                        <option value="${product['id']}">${product['name']}</option>`;
            });
            newRow += `
                    </select>
                </td>
                <td>
                    <input type="text" name="desc[]" value="">
                </td>
                <td>
                    <input type="number" disabled name="price[]" value="">
                </td>
                <td>
                    <input type="number" name="quantity[]" value="">
                </td>
                <td>
                    <input type="number" disabled name="subtotal[]" value="">
                </td>
                <td>
                    <button type="button" class="btn btn-danger delete-detail">Delete</button>
                </td>
            </tr>`;
            $(this).siblings('.details-table').find('tbody').append(newRow);
            $(this).closest('.modal').modal('handleUpdate'); // readjust modal height
        });

        // quantity-detail changed
        $(document).on('change', '[name="quantity[]"]', function() {
            var tr = $(this).closest('tr');
            var uploadForm = $(this).closest('form');
            calculateSubtotal(tr);
            calculateTotal(uploadForm);
        });

        // product-detail changed
        $(document).on('change', '[name="product_id[]"]', function() {
            var url = "{{ route('product.edit', ['product' => ':id']) }}".replace(':id', this.value);

            var tr = $(this).closest('tr');
            var uploadForm = $(this).closest('form');
            var priceInput = tr.find('input[name="price[]"]');

            $.ajax({
                type: "get",
                url: url,
                success: function(response) {
                    priceInput.val(response.price);
                    calculateSubtotal(tr);
                    calculateTotal(uploadForm);
                }
            });
        });

        // calculate subtotal of row
        function calculateSubtotal(row) {
            var price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            var quantity = parseInt(row.find('input[name="quantity[]"]').val()) || 0;
            var subtotal = price * quantity;
            row.find('input[name="subtotal[]"]').val(subtotal);
        }

        // calculate total of all subtotal
        function calculateTotal(uploadForm) {
            var total = 0;
            uploadForm.find('input[name="subtotal[]"]').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            uploadForm.find('[name="total"]').val(total);
        }

        // DELETE project SUBMITTED
        // HARUS EVENT DELEGATION
        $(document).on('submit', '.delete-project-form', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var formData = new FormData(this);
            var url = "{{ route('project.destroy', ['project' => ':id']) }}".replace(':id', id);

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
                                content: 'delete project failed',
                                type: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
