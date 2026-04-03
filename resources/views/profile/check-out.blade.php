<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Mark Check-Out</h2>
                <p class="text-sm text-gray-500 mb-6">
                    Click the button below to mark your check-out using your current system time.
                </p>

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

                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Employee</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $employee->full_name }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Today Check-In</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $todayAttendance->check_in }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Assigned Shift</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">
                            {{ optional($employee->shift)->name ?? 'No Shift Assigned' }}
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.checkout.store') }}" class="space-y-5">
                    @csrf

                    <div class="rounded-lg bg-blue-50 text-blue-800 px-4 py-3 text-sm">
                        Please ensure you are marking check-out from the office premises. If you are working remotely, make sure to mark check-out at the end of your workday to accurately reflect your attendance.
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Mark Check Out
                        </button>

                        <a href="{{ route('dashboard') }}"
                           class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>