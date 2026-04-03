<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Attendance Records</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        View employee attendance with shift, late, overtime, break and worked minutes.
                    </p>
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

                <form method="GET" action="{{ route('attendances.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <input type="text" name="employee" value="{{ request('employee') }}"
                                   placeholder="Employee name/email"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <select name="status"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Statuses</option>
                                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                                <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                                <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                            </select>
                        </div>

                        <div>
                            <select name="shift_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Shifts</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ (string) request('shift_id') === (string) $shift->id ? 'selected' : '' }}>
                                        {{ $shift->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Filter
                        </button>

                        <a href="{{ route('attendances.index') }}"
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            Reset
                        </a>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Shift</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check In</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check Out</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Late</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Overtime</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Break</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Worked</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($attendances as $attendance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $attendance->attendance_date->format('Y-m-d') }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $attendance->employee?->full_name ?? $attendance->employee?->user?->name ?? 'N/A' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $attendance->shift?->name ?? $attendance->employee?->shift?->name ?? 'N/A' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $attendance->check_in ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $attendance->check_out ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if($attendance->status === 'late')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Late
                                            </span>
                                        @elseif($attendance->status === 'present')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Present
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $attendance->late_minutes ?? 0 }} min
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $attendance->overtime_minutes ?? 0 }} min
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $attendance->break_minutes ?? 0 }} min
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $attendance->worked_minutes ?? 0 }} min
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No attendance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>