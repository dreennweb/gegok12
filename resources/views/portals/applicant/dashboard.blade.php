@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Single Window Business Registration Portal</h1>
            <p class="text-lg text-gray-600">Balochistan Government</p>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Welcome Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome {{ Auth::user()->name }}</h2>
                    <p class="text-gray-600 mb-6">Register your business with multiple Balochistan departments through a single application.</p>
                    
                    @if($applications->isEmpty())
                        <a href="{{ route('registrations.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                            Start New Registration
                        </a>
                    @else
                        <a href="{{ route('applications.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                            View Applications
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Total Applications</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2">{{ $applications->count() }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Pending Queries</div>
                    <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingQueries->count() }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Completed</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">{{ $applications->where('status', 'completed')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Recent Applications -->
        @if($applications->isNotEmpty())
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Recent Applications</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($applications->take(5) as $application)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $application->reference_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->business->legal_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold @switch($application->status)
                                    @case('completed')
                                        bg-green-100 text-green-800
                                    @break
                                    @case('rejected')
                                        bg-red-100 text-red-800
                                    @break
                                    @case('processing')
                                        bg-blue-100 text-blue-800
                                    @break
                                    @default
                                        bg-yellow-100 text-yellow-800
                                @endswitch">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $application->global_progress }}%"></div>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">{{ $application->global_progress }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('applications.show', $application) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Action Required -->
        @if($pendingQueries->isNotEmpty())
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Action Required</h3>
            <div class="space-y-4">
                @foreach($pendingQueries->take(5) as $query)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-gray-900">Query from {{ $query->departmentApplication->department->name }}</h4>
                            <p class="text-gray-700 mt-2">{{ $query->query_description }}</p>
                            <p class="text-sm text-gray-500 mt-4">Submitted: {{ $query->created_at->format('M d, Y') }}</p>
                        </div>
                        <a href="{{ route('applications.show', $query->departmentApplication->application_id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                            Respond →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
