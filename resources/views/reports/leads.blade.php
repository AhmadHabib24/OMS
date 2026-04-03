<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex flex-col gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Lead Reports</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Detailed lead report with filters and summaries.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('reports.leads') }}" class="grid grid-cols-1 md:grid-cols-7 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">From</label>
                            <input type="date" name="from" value="{{ request('from', $from->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">To</label>
                            <input type="date" name="to" value="{{ request('to', $to->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>Qualified</option>
                                <option value="proposal_sent" {{ request('status') === 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                                <option value="won" {{ request('status') === 'won' ? 'selected' : '' }}>Won</option>
                                <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Priority</label>
                            <select name="priority" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Priority</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Assigned To</label>
                            <select name="assigned_to" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('assigned_to') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Source</label>
                            <select name="source" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Sources</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source }}" {{ request('source') === $source ? 'selected' : '' }}>
                                        {{ $source }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Archived</label>
                            <select name="archived" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All</option>
                                <option value="no" {{ request('archived') === 'no' ? 'selected' : '' }}>Active Only</option>
                                <option value="yes" {{ request('archived') === 'yes' ? 'selected' : '' }}>Archived Only</option>
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs text-gray-500 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Code, name, email, phone, company..."
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="md:col-span-2 flex flex-col justify-end gap-2">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="follow_up_due_only" value="1"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                       {{ request('follow_up_due_only') ? 'checked' : '' }}>
                                Follow-up Due Only
                            </label>
                        </div>

                        <div class="md:col-span-2 flex items-end gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Apply
                            </button>

                            <a href="{{ route('reports.leads') }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                Reset
                            </a>
                            <a href="{{ route('reports.leads.export', request()->query()) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Export CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Total</p>
                    <h4 class="text-3xl font-bold text-gray-800 mt-2">{{ $summary['total'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">New</p>
                    <h4 class="text-3xl font-bold text-indigo-600 mt-2">{{ $summary['new'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Qualified</p>
                    <h4 class="text-3xl font-bold text-green-600 mt-2">{{ $summary['qualified'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Won</p>
                    <h4 class="text-3xl font-bold text-emerald-600 mt-2">{{ $summary['won'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Lost</p>
                    <h4 class="text-3xl font-bold text-red-600 mt-2">{{ $summary['lost'] }}</h4>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Contacted</p>
                    <h4 class="text-2xl font-bold text-blue-600 mt-2">{{ $summary['contacted'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Proposal Sent</p>
                    <h4 class="text-2xl font-bold text-purple-600 mt-2">{{ $summary['proposal_sent'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">High Priority</p>
                    <h4 class="text-2xl font-bold text-red-600 mt-2">{{ $summary['high_priority'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Follow-up Due</p>
                    <h4 class="text-2xl font-bold text-yellow-600 mt-2">{{ $summary['follow_up_due'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Archived</p>
                    <h4 class="text-2xl font-bold text-gray-600 mt-2">{{ $summary['archived'] }}</h4>
                </div>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Company</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Source</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Priority</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Follow Up</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Archived</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($records as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $record->lead_code }}</td>

                                    <td class="px-4 py-3 text-sm">
                                        <div class="font-medium text-gray-800">{{ $record->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $record->email ?? '-' }}</div>
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $record->company ?? '-' }}</td>

                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $record->source ?? '-' }}</td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ ucwords(str_replace('_', ' ', $record->status)) }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if($record->priority === 'high')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                High
                                            </span>
                                        @elseif($record->priority === 'medium')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Medium
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                                Low
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $record->assignedEmployee->full_name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $record->next_follow_up_date ? $record->next_follow_up_date->format('Y-m-d') : '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if($record->is_archived)
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">
                                                Yes
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                No
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No lead records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $records->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>