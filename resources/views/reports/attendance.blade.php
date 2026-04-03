<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <div class="bg-white shadow rounded-xl p-6">
                    <h2 class="text-2xl font-bold text-gray-800">Attendance Report</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Review attendance totals including late, overtime, break and worked minutes.
                    </p>
                </div>

                <div class="bg-white shadow rounded-xl p-6">
                    <form method="GET" action="{{ route('reports.attendance') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
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

                            <div class="flex items-end gap-2">
                                <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                    Filter
                                </button>

                                <a href="{{ route('reports.attendance') }}"
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white shadow rounded-xl p-5">
                        <p class="text-sm text-gray-500">Total Records</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $summary['total_records'] }}</p>
                    </div>

                    <div class="bg-white shadow rounded-xl p-5">
                        <p class="text-sm text-gray-500">Present Count</p>
                        <p class="text-2xl font-bold text-green-700 mt-1">{{ $summary['present_count'] }}</p>
                    </div>

                    <div class="bg-white shadow rounded-xl p-5">
                        <p class="text-sm text-gray-500">Late Count</p>
                        <p class="text-2xl font-bold text-yellow-700 mt-1">{{ $summary['late_count'] }}</p>
                    </div>

                    <div class="bg-white shadow rounded-xl p-5">
                        <p class="text-sm text-gray-500">Late Minutes</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $summary['total_late_minutes'] }}</p>
                    </div>

                    <div class="bg-white shadow rounded-xl p-5">
                        <p class="text-sm text-gray-500">Overtime Minutes</p>
                        <p class="text-2xl font-bold text-indigo-700 mt-1">{{ $summary['total_overtime_minutes'] }}</p>
                    </div>

                    <div class="bg-white shadow rounded-xl p-5">
                        <p class="text-sm text-gray-500">Break Minutes</p>
                        <p class="text-2xl font-bold text-red-700 mt-1">{{ $summary['total_break_minutes'] }}</p>
                    </div>

                    <div class="bg-white shadow rounded-xl p-5">
                        <p class="text-sm text-gray-500">Worked Minutes</p>
                        <p class="text-2xl font-bold text-blue-700 mt-1">{{ $summary['total_worked_minutes'] }}</p>
                    </div>
                </div>

                <div class="bg-white shadow rounded-xl p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Shift</th>
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
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->attendance_date->format('Y-m-d') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->employee?->full_name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->shift?->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($attendance->status) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->late_minutes ?? 0 }} min</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->overtime_minutes ?? 0 }} min</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->break_minutes ?? 0 }} min</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $attendance->worked_minutes ?? 0 }} min</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                                            No attendance report data found.
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
    </div>
</x-app-layout>