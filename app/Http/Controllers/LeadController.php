<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LeadController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Lead');
        Session::put('menu', 'Lead');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = Lead::all();
        if (request()->ajax()) {
            return $leads;
        }

        $levels = ['Sales', 'Manager'];
        return view('pages.lead.index', compact('leads'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:leads'],
            'contact' => ['required'],
        ]);

        Lead::create($validated);
        return Response()->json([
            'content' => 'Lead ' . $validated['name'] . ' added!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        return Response()->json($lead);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('leads', 'name')->ignore($id)
            ],
            'contact' => ['required']
        ]);

        $lead = Lead::find($id);
        $lead->update($validated);

        return Response()->json([
            'content' => 'Lead ' . $validated['name'] . ' updated!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return Response()->json([
            'content' => 'Lead ' . $lead['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
