<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Services\AllocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AllocationController extends Controller
{
    public function update(Request $request, Allocation $allocation, AllocationService $allocationService): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:accepted,overridden'],
            'reason' => ['nullable', 'string', 'max:1000', 'required_if:status,overridden'],
        ]);

        if ($validated['status'] === 'accepted') {
            $allocationService->accept($allocation, $request->user());

            return back();
        }

        if (! filled($validated['reason'] ?? null)) {
            throw ValidationException::withMessages([
                'reason' => 'A reason is required when overriding an allocation.',
            ]);
        }

        $allocationService->override($allocation, $request->user(), $validated['reason']);

        return back();
    }
}
