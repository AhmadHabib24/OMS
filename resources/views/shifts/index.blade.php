<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Shifts</h2>
                        <p class="text-sm text-gray-500 mt-1">Manage office shifts and break timings.</p>
                    </div>

                    <a href="{{ route('shifts.create') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Create Shift
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

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Timing</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Overnight</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Break</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Grace</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($shifts as $shift)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                        {{ $shift->name }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        @if($shift->is_overnight)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                                Overnight
                                            </span>
                                        @else
                                            <span class="text-gray-400">No</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        @if($shift->break_start_time && $shift->break_end_time)
                                            {{ \Carbon\Carbon::parse($shift->break_start_time)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($shift->break_end_time)->format('h:i A') }}
                                        @else
                                            <span class="text-gray-400">No break</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $shift->grace_minutes }} min
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if($shift->is_active)
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('shifts.edit', $shift) }}"
                                               class="px-3 py-1.5 rounded-md bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('shifts.destroy', $shift) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this shift?')">
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
                                    <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No shifts found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $shifts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>