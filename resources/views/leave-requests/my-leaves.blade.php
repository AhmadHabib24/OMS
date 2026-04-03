<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">My Leave Requests</h2>
                        <p class="text-sm text-gray-500 mt-1">View and manage your leave requests.</p>
                    </div>

                    <a href="{{ route('leave-requests.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Apply Leave
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="GET" action="{{ route('leave-requests.my') }}" class="mb-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-64">
                            <select name="status"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Filter
                            </button>

                            <a href="{{ route('leave-requests.my') }}"
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Leave Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Start Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">End Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Days</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Reason</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Admin Remarks</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($leaveRequests as $leave)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $leave->leaveType->name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $leave->start_date->format('Y-m-d') }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $leave->end_date->format('Y-m-d') }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $leave->total_days }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if($leave->status === 'pending')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Pending
                                            </span>
                                        @elseif($leave->status === 'approved')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Approved
                                            </span>
                                        @elseif($leave->status === 'rejected')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                Rejected
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700 max-w-xs">
                                        {{ $leave->reason ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700 max-w-xs">
                                        {{ $leave->admin_remarks ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex justify-end gap-2">
                                            @if($leave->status === 'pending')
                                                <form method="POST" action="{{ route('leave-requests.cancel', $leave) }}"
                                                      onsubmit="return confirm('Cancel this leave request?')">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-md bg-red-100 text-red-700 hover:bg-red-200">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">No action</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No leave requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $leaveRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>