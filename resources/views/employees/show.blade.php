<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $employee->full_name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Employee Details</p>
                    </div>

                    <a href="{{ route('employees.edit', $employee) }}"
                       class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                        Edit
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                        <p class="text-sm text-gray-500">Status</p>
                        <div class="mt-2">
                            @if($employee->status === 'active')
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('employees.index') }}"
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        Back to Employees
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>