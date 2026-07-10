@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ Auth::user()->departments->first()?->name }} - Staff Portal</h1>
            <p class="text-gray-600 mt-2">Review and process business registration applications</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 uppercase tracking-wide">Total Applications</div>
                <div class="text-3xl font-bold text-gray-900 mt-2">{{ $applications->total() }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 uppercase tracking-wide">Pending Review</div>
                <div class="text-3xl font-bold text-blue-600 mt-2">{{ $pendingCount }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 uppercase tracking-wide">Pending Queries</div>
                <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $queriedCount }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <a href="#" class="text-sm text-gray-500 uppercase tracking-wide">Export Data</a>
                <div class="text-lg font-semibold text-indigo-600 mt-2">Excel Export</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow mb-8 p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_review">In Review</option>
                        <option value="queried">Queried</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" placeholder="Reference or Business Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sort By</label>
                    <select name="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Filter</button>
                </div>
            </form>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($applications as $deptApp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900 font-mono text-sm">{{ $deptApp->application->reference_number }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $deptApp->application->business->legal_name }}</div>
                            <div class="text-sm text-gray-500">{{ $deptApp->application->business->ntn }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold @switch($deptApp->status)
                                @case('approved')
                                    bg-green-100 text-green-800
                                @break
                                @case('rejected')
                                    bg-red-100 text-red-800
                                @break
                                @case('queried')
                                    bg-yellow-100 text-yellow-800
                                @break
                                @case('in_review')
                                    bg-blue-100 text-blue-800
                                @break
                                @default
                                    bg-gray-100 text-gray-800
                            @endswitch">
                                {{ ucfirst(str_replace('_', ' ', $deptApp->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $deptApp->assignee?->name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Review</a>
                            <a href="#" class="text-green-600 hover:text-green-900">Approve</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No applications found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($applications->hasPages())
        <div class="mt-6">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
