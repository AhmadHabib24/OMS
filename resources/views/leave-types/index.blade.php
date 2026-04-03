<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Leave Types</h2>
                        <p class="text-sm text-gray-500 mt-1">Manage leave policies and yearly limits.</p>
                    </div>

                    <a href="{{ route('leave-types.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Add Leave Type
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Default Days</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paid</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Active</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($leaveTypes as $type)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $type->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $type->code }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $type->default_days }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($type->is_paid)
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Paid
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                                Unpaid
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($type->is_active)
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 max-w-xs">
                                        {{ $type->description ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('leave-types.edit', $type) }}"
                                               class="px-3 py-1.5 rounded-md bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                                                Edit
                                            </a>

                                            <form action="{{ route('leave-types.destroy', $type) }}" method="POST"
                                                  onsubmit="return confirm('Delete this leave type?')">
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
                                        No leave types found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $leaveTypes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>