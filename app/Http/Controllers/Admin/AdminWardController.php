<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Ward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminWardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Wards', [
            'wards' => Ward::withCount('beds')->with('beds')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:wards,name'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'total_beds' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $ward = Ward::create($validated);

        for ($index = 1; $index <= $ward->total_beds; $index++) {
            Bed::create([
                'ward_id' => $ward->id,
                'bed_number' => sprintf('%02d', $index),
                'status' => 'vacant',
            ]);
        }

        return back()->with('status', 'Ward created.');
    }

    public function update(Request $request, Ward $ward): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:wards,name,'.$ward->id],
            'specialization' => ['nullable', 'string', 'max:255'],
            'total_beds' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $ward->update($validated);

        return back()->with('status', 'Ward updated.');
    }

    public function destroy(Ward $ward): RedirectResponse
    {
        $ward->delete();

        return back()->with('status', 'Ward deleted.');
    }
}
