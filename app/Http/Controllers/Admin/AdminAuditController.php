<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminAuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('action')) {
            $query->where('action', 'like', '%'.$request->string('action').'%');
        }

        if ($request->filled('search')) {
            $search = $request->string('search');

            $query->where(function ($builder) use ($search) {
                $builder->where('subject_type', 'like', '%'.$search.'%')
                    ->orWhere('action', 'like', '%'.$search.'%');
            });
        }

        $visibleLogs = (clone $query)->latest()->limit(100)->get();

        $summary = [
            'matchingTotal' => (clone $query)->count(),
            'visibleTotal' => $visibleLogs->count(),
            'allocationEvents' => $visibleLogs->filter(fn ($log) => str_contains($log->action, 'allocation'))->count(),
            'triageEvents' => $visibleLogs->filter(fn ($log) => str_contains($log->action, 'triage'))->count(),
            'systemEvents' => $visibleLogs->whereNull('user_id')->count(),
            'uniqueUsers' => $visibleLogs->pluck('user_id')->filter()->unique()->count(),
            'subjectTypes' => $visibleLogs->groupBy('subject_type')->map->count()->sortDesc()->take(5)->map(fn ($count, $type) => [
                'label' => class_basename($type),
                'count' => $count,
            ])->values(),
            'topActions' => $visibleLogs->groupBy('action')->map->count()->sortDesc()->take(5)->map(fn ($count, $action) => [
                'label' => $action,
                'count' => $count,
            ])->values(),
        ];

        return Inertia::render('Admin/Audit', [
            'auditLogs' => $visibleLogs,
            'summary' => $summary,
            'filters' => $request->only(['action', 'search']),
        ]);
    }
}
