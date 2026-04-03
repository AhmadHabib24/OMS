<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Leads</h2>
                        <p class="text-sm text-gray-500 mt-1">Manage and track all business leads.</p>
                    </div>

                    <a href="{{ route('leads.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Add Lead
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="GET" action="{{ route('leads.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search lead..."
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>Qualified</option>
                            <option value="proposal_sent" {{ request('status') === 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                            <option value="won" {{ request('status') === 'won' ? 'selected' : '' }}>Won</option>
                            <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
                        </select>

                        <select name="priority" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Priority</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        </select>

                        <select name="assigned_to" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Assignees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('assigned_to') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Filter
                            </button>
                            <a href="{{ route('leads.index') }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Company</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Priority</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned To</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Follow Up</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($leads as $lead)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $lead->lead_code }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $lead->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $lead->company ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ucwords(str_replace('_', ' ', $lead->status)) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($lead->priority) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $lead->assignedEmployee->full_name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $lead->next_follow_up_date ? $lead->next_follow_up_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('leads.show', $lead) }}"
                                               class="px-3 py-1.5 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200">
                                                View
                                            </a>
                                            <a href="{{ route('leads.edit', $lead) }}"
                                               class="px-3 py-1.5 rounded-md bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('leads.destroy', $lead) }}" method="POST"
                                                  onsubmit="return confirm('Delete this lead?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1.5 rounded-md bg-red-100 text-red-700 hover:bg-red-200">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No leads found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $leads->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>