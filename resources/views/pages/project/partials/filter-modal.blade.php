<div class="modal fade" id="filter-project-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Filter project</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="filter-project-form" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filter-status">Status:</label>
                        <select id="filter-status" type="text" placeholder="project status" class="form-control"
                            name="status">
                            <option value=""></option>
                            @foreach ($status as $state)
                                <option value="{{ $state }}" @selected(request()->status == $state)>{{ $state }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filter-manager_id">manager:</label>
                        <select id="filter-manager_id" type="text" placeholder="project manager" class="form-control"
                            name="manager_id">
                            <option value=""></option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}" @selected(request()->manager_id == $manager->id)>{{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filter-sales_id">sales:</label>
                        <select id="filter-sales_id" type="text" placeholder="project sales" class="form-control"
                            name="sales_id">
                            <option value=""></option>
                            @foreach ($sales as $sl)
                                <option value="{{ $sl->id }}" @selected(request()->sales_id == $sl->id)>{{ $sl->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filter-lead_id">lead:</label>
                        <select id="filter-lead_id" type="text" placeholder="project lead" class="form-control"
                            name="lead_id">
                            <option value=""></option>
                            @foreach ($leads as $lead)
                                <option value="{{ $lead->id }}" @selected(request()->lead_id == $lead->id)>{{ $lead->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
