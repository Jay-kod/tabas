<?php

namespace App\Http\Controllers\BedManager;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Bed;
use App\Models\Ward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BedBoardController extends Controller
{
    public function index()
    {
        return Inertia::render('BedManager/Beds', [
            'wards' => Ward::with(['beds' => fn ($query) => $query->orderBy('bed_number')])->orderBy('name')->get(),
            'pendingAllocations' => Allocation::with(['triageRecord.patient', 'recommendedBed.ward'])
                ->where('status', 'pending')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ward_id' => ['required', 'exists:wards,id'],
            'bed_number' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:vacant,occupied,reserved'],
        ]);

        Bed::create($validated);

        return back()->with('status', 'Bed created.');
    }

    public function update(Request $request, Bed $bed): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:vacant,occupied,reserved'],
        ]);

        $bed->update($validated);

        return back()->with('status', 'Bed updated.');
    }

    public function destroy(Bed $bed): RedirectResponse
    {
        $bed->delete();

        return back()->with('status', 'Bed deleted.');
    }
}
