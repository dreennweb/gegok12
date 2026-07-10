<?php

namespace App\Livewire;

use App\Models\Business;
use App\Models\Department;
use App\Services\RegistrationService;
use Livewire\Component;
use Livewire\WithFileUploads;

class MultiStepRegistrationForm extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 5;

    // Step 1: Business Information
    public $legal_name = '';
    public $business_type = '';
    public $ntn = '';
    public $cnic = '';
    public $business_description = '';
    public $business_nature = [];

    // Step 2: Department Selection
    public $selected_departments = [];
    public $has_existing_registration = false;
    public $existing_registrations = [];

    // Step 3: Worker Information
    public $has_workers = false;
    public $worker_count = '';

    // Step 4: Document Upload
    public $documents = [];
    public $uploadedDocuments = [];

    // Step 5: Review & Submit
    public $agreedToTerms = false;

    protected $rules = [
        'legal_name' => ['required', 'string', 'max:255'],
        'business_type' => ['required', 'in:company,aop,sole_proprietorship'],
        'ntn' => ['nullable', 'string'],
        'cnic' => ['nullable', 'string'],
        'business_description' => ['nullable', 'string', 'max:1000'],
        'business_nature' => ['required', 'array', 'min:1'],
        'selected_departments' => ['required', 'array', 'min:1'],
        'has_workers' => ['required', 'boolean'],
        'worker_count' => ['required_if:has_workers,true', 'nullable', 'integer', 'min:1'],
        'agreedToTerms' => ['required', 'accepted'],
    ];

    public function mount()
    {
        if (auth()->check()) {
            $this->loadBusinessData();
        }
    }

    public function render()
    {
        return view('livewire.multi-step-registration-form', [
            'departments' => Department::active()->get(),
            'businessTypes' => ['company' => 'Company (SECP)', 'aop' => 'Association of Persons', 'sole_proprietorship' => 'Sole Proprietorship/Firm'],
            'businessNatures' => ['trading' => 'Trading', 'manufacturing' => 'Manufacturing', 'services' => 'Services', 'food' => 'Food & Beverages', 'healthcare' => 'Healthcare', 'excise' => 'Excise Goods'],
        ]);
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validateOnly([
                'legal_name',
                'business_type',
                'business_nature',
                'business_description',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validateOnly('selected_departments');
        } elseif ($this->currentStep === 3) {
            if ($this->has_workers) {
                $this->validateOnly('worker_count');
            }
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            $business = Business::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'legal_name' => $this->legal_name,
                    'business_type' => $this->business_type,
                    'ntn' => $this->ntn,
                    'cnic' => $this->cnic,
                    'business_description' => $this->business_description,
                    'business_nature' => $this->business_nature,
                    'selected_departments' => $this->selected_departments,
                    'has_workers' => $this->has_workers,
                    'worker_count' => $this->worker_count,
                ]
            );

            $registrationService = new RegistrationService();
            $application = $registrationService->createApplication($business, $this->selected_departments);

            $this->dispatch('registration-complete', referenceNumber: $application->reference_number);
            redirect()->route('applications.show', $application);
        } catch (\Exception $e) {
            $this->addError('submission', 'Error submitting application: ' . $e->getMessage());
        }
    }

    public function uploadDocument($file, $documentType)
    {
        $this->validate([
            'documents.' . $documentType => 'required|file|max:10240|mimes:pdf,doc,docx,xlsx,jpg,jpeg,png',
        ]);

        $this->uploadedDocuments[$documentType] = [
            'file' => $file,
            'type' => $documentType,
        ];
    }

    private function loadBusinessData()
    {
        $business = auth()->user()->businesses()->latest()->first();
        if ($business) {
            $this->legal_name = $business->legal_name;
            $this->business_type = $business->business_type;
            $this->ntn = $business->ntn;
            $this->cnic = $business->cnic;
            $this->business_description = $business->business_description;
            $this->business_nature = $business->business_nature ?? [];
            $this->selected_departments = $business->selected_departments ?? [];
            $this->has_workers = $business->has_workers;
            $this->worker_count = $business->worker_count;
        }
    }
}
