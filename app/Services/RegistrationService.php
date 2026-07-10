<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Business;
use App\Models\Department;
use App\Models\DepartmentApplication;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class RegistrationService
{
    /**
     * Create a new business registration application
     */
    public function createApplication(Business $business, array $selectedDepartments): Application
    {
        $application = Application::create([
            'business_id' => $business->id,
            'reference_number' => $this->generateReferenceNumber(),
            'status' => 'draft',
        ]);

        // Create department-specific applications
        foreach ($selectedDepartments as $departmentId) {
            DepartmentApplication::create([
                'application_id' => $application->id,
                'department_id' => $departmentId,
                'status' => 'pending',
            ]);
        }

        return $application;
    }

    /**
     * Route business based on nature and size
     */
    public function routeBusiness(Business $business): array
    {
        $routedDepartments = [];

        // Determine if Withholding Agent or Service Provider for BRA
        if ($this->shouldRegisterWithBRA($business)) {
            $routedDepartments[] = Department::byCode('bra')->first()?->id;
        }

        // Add WWF if has workers
        if ($business->has_workers && $business->worker_count > 0) {
            // WWF is handled through BRA
        }

        // Add Labour Department if has workers
        if ($business->has_workers) {
            $routedDepartments[] = Department::byCode('labour')->first()?->id;
        }

        // Add other departments based on business nature
        if (in_array('excise', $business->business_nature ?? [])) {
            $routedDepartments[] = Department::byCode('excise')->first()?->id;
        }

        if (in_array('food', $business->business_nature ?? [])) {
            $routedDepartments[] = Department::byCode('bfa')->first()?->id;
        }

        if (in_array('healthcare', $business->business_nature ?? [])) {
            $routedDepartments[] = Department::byCode('bhc')->first()?->id;
        }

        return array_filter(array_unique($routedDepartments));
    }

    /**
     * Determine if business should register with BRA
     */
    private function shouldRegisterWithBRA(Business $business): bool
    {
        // All businesses need to register with BRA
        return true;
    }

    /**
     * Generate unique reference number
     */
    public function generateReferenceNumber(): string
    {
        $year = date('Y');
        $random = Str::random(6);
        return "BRP-{$year}-{$random}";
    }

    /**
     * Calculate application progress
     */
    public function calculateProgress(Application $application): int
    {
        $departments = $application->departmentApplications()->count();
        if ($departments === 0) return 0;

        $approved = $application->departmentApplications()
            ->where('status', 'approved')
            ->count();

        return (int) (($approved / $departments) * 100);
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(Application $application): void
    {
        $departments = $application->departmentApplications()->get();

        $statuses = $departments->pluck('status')->unique()->toArray();

        if (count($statuses) === 1 && $statuses[0] === 'approved') {
            $application->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        } elseif (in_array('rejected', $statuses)) {
            $application->update(['status' => 'rejected']);
        } elseif (in_array('processing', $statuses)) {
            $application->update(['status' => 'processing']);
        }
    }
}
