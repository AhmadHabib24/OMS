<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex flex-col gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Leave Requests</h2>
                        <p class="text-sm text-gray-500 mt-1">Review and manage employee leave requests.</p>
                    </div>

                    @if(session('success'))
                        <div class="rounded-lg bg-green-100 text-green-800 px-4 py-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="rounded-lg bg-red-100 text-red-800 px-4 py-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-lg bg-red-100 text-red-800 px-4 py-3">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('leave-requests.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Status</label>
                            <select name="status"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Employee</label>
                            <select name="employee_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Leave Type</label>
                            <select name="leave_type_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Types</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">From</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">To</label>
                            <input type="date" name="to" value="{{ request('to') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Apply
                            </button>

                            <a href="{{ route('leave-requests.index') }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                Reset
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
                    <p class="text-sm text-gray-500">Pending</p>
                    <h4 class="text-3xl font-bold text-yellow-600 mt-2">{{ $summary['pending'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Approved</p>
                    <h4 class="text-3xl font-bold text-green-600 mt-2">{{ $summary['approved'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Rejected</p>
                    <h4 class="text-3xl font-bold text-red-600 mt-2">{{ $summary['rejected'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Cancelled</p>
                    <h4 class="text-3xl font-bold text-gray-600 mt-2">{{ $summary['cancelled'] }}</h4>
                </div>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Leave Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Start Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">End Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Days</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Reason</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Remarks</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($leaveRequests as $leave)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $leave->employee->full_name ?? '-' }}
                                    </td>

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
                                        @if($leave->status === 'pending')
                                            <div class="flex justify-end gap-2">
                                                <form method="POST" action="{{ route('leave-requests.approve', $leave) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-md bg-green-100 text-green-700 hover:bg-green-200">
                                                        Approve
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('leave-requests.reject', $leave) }}" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="text" name="admin_remarks"
                                                           placeholder="Reject reason"
                                                           class="w-36 rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">

                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-md bg-red-100 text-red-700 hover:bg-red-200">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-xs text-gray-400 text-right">
                                                {{ $leave->approver->name ?? 'Processed' }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">
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