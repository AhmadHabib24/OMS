<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Attendance Details</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $attendance->employee->full_name }}</p>
                    </div>

                    <a href="{{ route('attendances.edit', $attendance) }}"
                       class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                        Edit
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Attendance Date</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->attendance_date->format('Y-m-d') }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ ucfirst($attendance->status) }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Check In</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->check_in ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Check Out</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->check_out ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">IP Address</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->ip_address ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Device</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->device_name ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Browser</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->browser ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Platform</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->platform ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Latitude</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->latitude ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Longitude</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->longitude ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                        <p class="text-sm text-gray-500">Suspicious Reason</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->suspicious_reason ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                        <p class="text-sm text-gray-500">Admin Review Note</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $attendance->admin_review_note ?? '-' }}</p>
                    </div>
                </div>

                @if($attendance->photo_path)
                    <div class="mt-6">
                        <p class="text-sm text-gray-500 mb-2">Selfie Proof</p>
                        <img src="{{ asset('storage/' . $attendance->photo_path) }}"
                             alt="Attendance Selfie"
                             class="w-48 h-48 object-cover rounded-xl border border-gray-200 shadow">
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('attendances.index') }}"
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        Back to Attendance
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>