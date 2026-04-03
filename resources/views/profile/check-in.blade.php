<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Mark My Attendance</h2>
                <p class="text-sm text-gray-500 mb-6">
                    Click the button below to mark your check-in using your current system time and location.
                </p>

                @if(session('error'))
                    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
                        {{ session('success') }}
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

                @if($todayAttendance)
                    <div class="rounded-lg bg-yellow-100 text-yellow-800 px-4 py-3">
                        You have already marked attendance for today.
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('profile.attendance') }}"
                           class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            View My Attendance
                        </a>
                    </div>
                @else
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Employee</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">{{ $employee->full_name }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">{{ now()->format('Y-m-d') }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Assigned Shift</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">
                                {{ optional($employee->shift)->name ?? 'No Shift Assigned' }}
                            </p>
                        </div>
                    </div>

                    <form id="checkinForm" method="POST" action="{{ route('profile.checkin.store') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Selfie Proof</label>
                            <input type="file" name="photo" accept="image/*" capture="user"
                                   class="block w-full text-sm text-gray-700
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100">
                            <p class="text-xs text-gray-500 mt-1">
                                Please take a selfie to verify your identity during check-in when you are noot in the office premises. This helps us ensure that attendance is marked accurately and prevents fraudulent check-ins.
                            </p>
                        </div>

                        <div class="rounded-lg bg-blue-50 text-blue-800 px-4 py-3 text-sm">
                            Please allow location access to mark your attendance. Your current location will be captured along with the check-in time.
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="button"
                                    onclick="submitCheckInWithLocation()"
                                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Mark Check In
                            </button>

                            <a href="{{ route('dashboard') }}"
                               class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                Cancel
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        function submitCheckInWithLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by this browser.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    document.getElementById('checkinForm').submit();
                },
                function() {
                    alert('Unable to capture location. Please allow location permission.');
                }
            );
        }
    </script>
</x-app-layout>