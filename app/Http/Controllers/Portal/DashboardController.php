<?php

namespace App\Http\Controllers\Portal;

use App\Models\Application;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display applicant dashboard
     */
    public function applicantDashboard(): View
    {
        $applications = auth()->user()
            ->businesses()
            ->with(['applications.departmentApplications.department'])
            ->get()
            ->flatMap(fn($b) => $b->applications);

        $pendingQueries = auth()->user()
            ->businesses()
            ->with(['applications.departmentApplications.queries'])
            ->get()
            ->flatMap(fn($b) => $b->applications)
            ->flatMap(fn($a) => $a->departmentApplications)
            ->flatMap(fn($da) => $da->queries)
            ->where('status', 'open');

        return view('portals.applicant.dashboard', [
            'applications' => $applications,
            'pendingQueries' => $pendingQueries,
        ]);
    }

    /**
     * Display department staff dashboard
     */
    public function departmentStaffDashboard(): View
    {
        $departments = auth()->user()->departments()->get();
        $departmentIds = $departments->pluck('id');

        $applications = $this->getDepartmentApplications($departmentIds);
        $pendingCount = $applications->where('status', 'pending')->count();
        $queriedCount = $applications->where('status', 'queried')->count();

        return view('portals.department.dashboard', [
            'departments' => $departments,
            'applications' => $applications,
            'pendingCount' => $pendingCount,
            'queriedCount' => $queriedCount,
        ]);
    }

    /**
     * Display superadmin dashboard
     */
    public function superadminDashboard(): View
    {
        $totalApplications = Application::count();
        $totalBusinesses = Business::count();
        $totalDepartments = Department::count();
        $recentApplications = Application::latest()->limit(10)->get();

        return view('portals.admin.dashboard', [
            'totalApplications' => $totalApplications,
            'totalBusinesses' => $totalBusinesses,
            'totalDepartments' => $totalDepartments,
            'recentApplications' => $recentApplications,
        ]);
    }

    private function getDepartmentApplications($departmentIds)
    {
        return \App\Models\DepartmentApplication::whereIn('department_id', $departmentIds)
            ->with('application.business', 'department', 'assignee')
            ->latest()
            ->paginate(15);
    }
}
