<div class="modal fade" id="show-lead-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Show Lead</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="show-lead-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="show-name">Name:</label>
                        <input id="show-name" type="text" class="form-control" disabled name="name">
                    </div>
                    <div class="mb-3">
                        <label for="show-contact">Contact:</label>
                        <input id="show-contact" type="text" class="form-control" disabled name="contact">
                    </div>
                    <div class="mb-3">
                        <label for="show-contact">Product Purchased:</label>
                        <table class="table" id="show-product-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
