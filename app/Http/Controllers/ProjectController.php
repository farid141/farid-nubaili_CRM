<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Project');
        Session::put('menu', 'Project');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::with(['manager', 'lead', 'sales'])->get();
        if (request()->ajax()) {
            return $projects;
        }
        $products = Product::all();
        $managers = User::where('level', 'Manager')->get();
        $leads = Lead::all();
        $status = ['pending', 'approved', 'rejected'];
        return view('pages.project.index', compact('projects', 'products', 'managers', 'leads', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:projects'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'manager_id' => ['required', 'exists:users,id'],
            'sales_id' => ['required', 'exists:users,id'],
            'lead_id' => ['required', 'exists:leads,id'],
            'start_date' => ['required', 'date'],
            'desc' => ['required'],
            'total' => ['required'],
            'details' => ['required'],
        ]);

        $project = Project::create($validated);
        foreach (json_decode($validated['details']) as $detail) {
            $detail = (array)$detail;
            $detail['project_id'] = $project->id;
            ProjectDetail::create($detail);
        }

        return Response()->json([
            'content' => 'project ' . $validated['name'] . ' added!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return $project;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $data = collect($project->load(['manager', 'lead', 'details.product']));
        return Response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('projects', 'name')->ignore($project->id),
            ],
            'status' => ['required', 'in:pending,approved,rejected'],
            'manager_id' => ['required', 'exists:users,id'],
            'sales_id' => ['required', 'exists:users,id'],
            'lead_id' => ['required', 'exists:leads,id'],
            'start_date' => ['required', 'date'],
            'desc' => ['required'],
            'total' => ['required'],
            'details' => ['required']
        ]);

        $project->update($validated);

        // Handle project details
        $existingDetailIds = $project->details->pluck('id')->toArray();
        $inputDetails = json_decode($request->details);
        $inputDetailIds = collect($inputDetails)->map(fn ($item) => (array)$item)->pluck('id')->toArray();

        // Delete details that were removed
        $detailsToDelete = array_diff($existingDetailIds, $inputDetailIds);
        ProjectDetail::destroy($detailsToDelete);

        // Update existing details and add new ones
        foreach ($inputDetails as $detail) {
            $detail = (array)$detail;
            if (isset($detail['id']) && in_array($detail['id'], $existingDetailIds)) {
                // Update existing detail
                $projectDetail = ProjectDetail::findOrFail($detail['id']);
                unset($detail['id']);
                $projectDetail->update($detail);
            } else {
                // Add new detail
                $project->details()->create($detail);
            }
        }

        return Response()->json([
            'content' => 'project ' . $validated['name'] . ' added!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return Response()->json([
            'content' => 'project ' . $project['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
