<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $complaints = Complaint::query()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->trim()->toString();

                $query->where(function ($query) use ($search): void {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->string('priority')->toString()))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $editingComplaint = $request->filled('edit')
            ? Complaint::findOrFail($request->integer('edit'))
            : null;

        return view('complaints.index', compact('complaints', 'editingComplaint'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreComplaintRequest $request): RedirectResponse
    {
        Complaint::create($request->validated());

        return to_route('complaints.index')->with('success', 'Complaint created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint): RedirectResponse
    {
        $complaint->update($request->validated());

        return to_route('complaints.index')->with('success', 'Complaint updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint): RedirectResponse
    {
        $complaint->delete();

        return to_route('complaints.index')->with('success', 'Complaint deleted successfully.');
    }
}
