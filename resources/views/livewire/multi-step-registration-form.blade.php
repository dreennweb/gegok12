<div>
    <form wire:submit="submit" class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Step {{ $currentStep }} of {{ $totalSteps }}</h2>
                <span class="text-sm text-gray-600">{{ round(($currentStep / $totalSteps) * 100) }}% Complete</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>
        </div>

        <!-- Step 1: Business Information -->
        @if($currentStep === 1)
        <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Business Information</h3>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Legal Business Name *</label>
                    <input type="text" wire:model="legal_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Enter your business legal name">
                    @error('legal_name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Type *</label>
                    <select wire:model="business_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Select Business Type</option>
                        @foreach($businessTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('business_type')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NTN (Optional)</label>
                        <input type="text" wire:model="ntn" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Tax Number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CNIC (Optional)</label>
                        <input type="text" wire:model="cnic" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="National ID">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Nature *</label>
                    <div class="space-y-2">
                        @foreach($businessNatures as $value => $label)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="business_nature" value="{{ $value }}" class="rounded border-gray-300">
                                <span class="ml-2 text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('business_nature')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Description</label>
                    <textarea wire:model="business_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Describe your business activities"></textarea>
                </div>
            </div>
        </div>
        @endif

        <!-- Step 2: Department Selection -->
        @if($currentStep === 2)
        <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Select Departments</h3>
            
            <div class="space-y-4">
                @foreach($departments as $department)
                <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" wire:model="selected_departments" value="{{ $department->id }}" class="mt-1 rounded border-gray-300">
                    <div class="ml-3">
                        <p class="font-semibold text-gray-900">{{ $department->name }}</p>
                        <p class="text-sm text-gray-600">{{ $department->description }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            @error('selected_departments')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
        </div>
        @endif

        <!-- Step 3: Worker Information -->
        @if($currentStep === 3)
        <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Worker Information</h3>
            
            <div class="space-y-6">
                <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer">
                    <input type="checkbox" wire:model="has_workers" class="rounded border-gray-300">
                    <span class="ml-3 text-gray-900 font-medium">Does your business have employees?</span>
                </label>

                @if($has_workers)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Employees *</label>
                    <input type="number" wire:model="worker_count" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Enter number of employees" min="1">
                    @error('worker_count')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-900">💡 Worker Welfare Fund (WWF) registration will be automatically included based on your employee count.</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Step 4: Document Upload -->
        @if($currentStep === 4)
        <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Required Documents</h3>
            
            <div class="space-y-6">
                <p class="text-gray-600">Please upload the following documents. All files must be in PDF, DOC, DOCX, XLSX, JPG, JPEG, or PNG format (Max 10MB each).</p>
                
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-indigo-500">
                        <input type="file" wire:model="documents" multiple class="hidden" accept=".pdf,.doc,.docx,.xlsx,.jpg,.jpeg,.png">
                        <p class="text-gray-600">📁 Click or drag files here to upload</p>
                        <p class="text-sm text-gray-500 mt-2">Supported formats: PDF, DOC, DOCX, XLSX, JPG, JPEG, PNG</p>
                    </div>
                </div>

                @if(!empty($uploadedDocuments))
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Uploaded Documents:</h4>
                    <div class="space-y-2">
                        @foreach($uploadedDocuments as $type => $doc)
                            <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                                <span class="text-green-900">✓ {{ ucfirst($type) }} uploaded</span>
                                <button type="button" wire:click="removeDocument('{{ $type }}')" class="text-red-600 hover:text-red-900">Remove</button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Step 5: Review & Submit -->
        @if($currentStep === 5)
        <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Review & Submit</h3>
            
            <div class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Business Name</p>
                        <p class="font-semibold text-gray-900">{{ $legal_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Business Type</p>
                        <p class="font-semibold text-gray-900">{{ $businessTypes[$business_type] ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Selected Departments</p>
                        <p class="font-semibold text-gray-900">
                            {{ implode(', ', array_map(fn($id) => $departments->find($id)?->name, $selected_departments)) }}
                        </p>
                    </div>
                </div>

                <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer">
                    <input type="checkbox" wire:model="agreedToTerms" class="mt-1 rounded border-gray-300">
                    <div class="ml-3">
                        <p class="text-gray-900 font-medium">I agree to the terms and conditions</p>
                        <p class="text-sm text-gray-600 mt-1">By submitting this application, you agree to provide accurate information and comply with all applicable regulations.</p>
                    </div>
                </label>
                @error('agreedToTerms')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>
        </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="flex justify-between items-center mt-8 pt-8 border-t border-gray-200">
            <button type="button" wire:click="previousStep" @if($currentStep === 1) disabled @endif class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 disabled:opacity-50">
                ← Previous
            </button>

            @if($currentStep < $totalSteps)
                <button type="button" wire:click="nextStep" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Next →
                </button>
            @else
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Submit Application
                </button>
            @endif
        </div>

        @if($errors->any())
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-900 font-semibold">Please correct the following errors:</p>
                <ul class="mt-2 list-disc list-inside text-red-800 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</div>
