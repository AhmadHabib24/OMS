<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">My Attendance</h2>
                    <p class="text-sm text-gray-500 mt-1">Track your attendance, late minutes, overtime and break time.</p>
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

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
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
                                        {{ $attendance->shift?->name ?? 'N/A' }}
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
                                    <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">
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