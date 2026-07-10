<?php

namespace App\Http\Controllers\API;

use App\Models\Application;
use App\Models\Business;
use App\Services\RegistrationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Get current user applications
     */
    public function index(Request $request)
    {
        $applications = auth()->user()
            ->businesses()
            ->with(['applications.departmentApplications'])
            ->get()
            ->flatMap(fn($b) => $b->applications)
            ->sortByDesc('created_at');

        return response()->json([
            'data' => $applications,
            'count' => $applications->count(),
        ]);
    }

    /**
     * Get application details
     */
    public function show(Application $application)
    {
        $this->authorize('view', $application);

        return response()->json([
            'data' => $application->load([
                'business',
                'departmentApplications.department',
                'departmentApplications.assignee',
                'departmentApplications.queries',
            ]),
        ]);
    }

    /**
     * Submit application
     */
    public function submit(Application $application)
    {
        $this->authorize('update', $application);

        if ($application->status !== 'draft') {
            return response()->json(['error' => 'Only draft applications can be submitted'], 422);
        }

        $application->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $progress = $this->registrationService->calculateProgress($application);
        $application->update(['global_progress' => $progress]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'data' => $application,
        ]);
    }
}
