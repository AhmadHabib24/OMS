<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Apply for Leave</h2>

                @if(session('error'))
                    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Current Leave Balance</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($balances as $balance)
                            <div class="rounded-xl border border-gray-200 p-4 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ $balance['name'] }}</p>
                                        <p class="font-semibold text-gray-800">{{ $balance['code'] }}</p>
                                    </div>

                                    <span class="text-sm font-bold text-green-600">
                                        {{ $balance['remaining_days'] }} left
                                    </span>
                                </div>

                                <div class="mt-3 text-xs text-gray-500">
                                    Allowed: {{ $balance['allowed_days'] }} |
                                    Used: {{ $balance['used_days'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('leave-requests.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                        <select name="leave_type_id"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Leave Type</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} ({{ $type->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                onchange="calculateLeaveDays()">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                onchange="calculateLeaveDays()">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Days</label>
                        <input type="text" id="total_days_display" readonly
                            class="w-full rounded-lg bg-gray-50 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Auto calculated">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                        <textarea name="reason" rows="5"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Write your leave reason...">{{ old('reason') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Submit Leave Request
                        </button>

                        <a href="{{ route('leave-requests.my') }}"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function calculateLeaveDays() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const display = document.getElementById('total_days_display');

            if (!startDate || !endDate) {
                display.value = '';
                return;
            }

            const start = new Date(startDate);
            const end = new Date(endDate);

            if (end < start) {
                display.value = 'Invalid date range';
                return;
            }

            const timeDiff = end.getTime() - start.getTime();
            const dayDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24)) + 1;

            display.value = dayDiff + ' day(s)';
        }

        window.addEventListener('load', calculateLeaveDays);
    </script>
</x-app-layout>