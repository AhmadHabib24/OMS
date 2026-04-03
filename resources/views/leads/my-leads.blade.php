<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">My Leads</h2>
                    <p class="text-sm text-gray-500 mt-1">Leads assigned to you.</p>
                </div>

                @if(session('error'))
                    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="GET" action="{{ route('leads.my') }}" class="mb-6">
                    <div class="flex gap-4">
                        <select name="status" class="w-full max-w-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>Qualified</option>
                            <option value="proposal_sent" {{ request('status') === 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                            <option value="won" {{ request('status') === 'won' ? 'selected' : '' }}>Won</option>
                            <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
                        </select>

                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Filter
                        </button>
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Follow Up</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($leads as $lead)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $lead->lead_code }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $lead->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $lead->company ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ucwords(str_replace('_', ' ', $lead->status)) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($lead->priority) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $lead->next_follow_up_date ? $lead->next_follow_up_date->format('Y-m-d') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No assigned leads found.
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