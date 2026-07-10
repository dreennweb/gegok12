@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Superadmin Dashboard</h1>
            <p class="text-gray-600 mt-2">Global system administration and monitoring</p>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 text-blue-600">
                        📊
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Total Applications</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalApplications }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 text-green-600">
                        🏢
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Total Businesses</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalBusinesses }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 text-indigo-600">
                        🏛️
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Total Departments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalDepartments }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 text-yellow-600">
                        ⚙️
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 uppercase tracking-wide">System Status</p>
                        <p class="text-2xl font-bold text-green-600">Operational</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Controls -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Management</h3>
                <ul class="space-y-3">
                    <li><a href="/admin/applications" class="text-indigo-600 hover:text-indigo-900">→ View All Applications</a></li>
                    <li><a href="/admin/departments" class="text-indigo-600 hover:text-indigo-900">→ Manage Departments</a></li>
                    <li><a href="/admin/users" class="text-indigo-600 hover:text-indigo-900">→ Manage Users</a></li>
                </ul>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Reports & Export</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-indigo-600 hover:text-indigo-900">→ Export All Applications</a></li>
                    <li><a href="#" class="text-indigo-600 hover:text-indigo-900">→ Audit Logs</a></li>
                    <li><a href="#" class="text-indigo-600 hover:text-indigo-900">→ System Reports</a></li>
                </ul>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Configuration</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-indigo-600 hover:text-indigo-900">→ System Settings</a></li>
                    <li><a href="#" class="text-indigo-600 hover:text-indigo-900">→ Email Templates</a></li>
                    <li><a href="#" class="text-indigo-600 hover:text-indigo-900">→ API Keys</a></li>
                </ul>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Applications</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentApplications as $app)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="/admin/applications/{{ $app->id }}" class="text-indigo-600 hover:text-indigo-900 font-mono text-sm">{{ $app->reference_number }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $app->business->legal_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($app->business->business_type) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold @switch($app->status)
                                @case('completed')
                                    bg-green-100 text-green-800
                                @break
                                @case('rejected')
                                    bg-red-100 text-red-800
                                @break
                                @default
                                    bg-blue-100 text-blue-800
                            @endswitch">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $app->submitted_at?->format('M d, Y') ?? 'Pending' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No applications found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
