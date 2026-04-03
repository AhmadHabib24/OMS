<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h2>

                @if($employee)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">{{ $employee->full_name }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Employee Code</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">{{ $employee->employee_code }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">{{ $employee->email }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">{{ $employee->phone ?? '-' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">{{ $employee->department ?? '-' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Designation</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">{{ $employee->designation ?? '-' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Joining Date</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">
                                {{ $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '-' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-base font-semibold text-gray-800 mt-1">{{ ucfirst($employee->status) }}</p>
                        </div>
                    </div>
                @else
                    <div class="rounded-lg bg-red-100 text-red-800 px-4 py-3">
                        Your employee profile is not linked yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>