<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Allowed IPs</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Manage IP-based login access for users.
                        </p>
                    </div>

                    <a href="{{ route('allowed-ips.create') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Add Allowed IP
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

                <form method="GET" action="{{ route('allowed-ips.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search by IP, label, user name, or email..."
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Filter
                            </button>

                            <a href="{{ route('allowed-ips.index') }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">IP Address</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Label</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($allowedIps as $allowedIp)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        @if($allowedIp->user)
                                            <div class="font-medium text-gray-800">{{ $allowedIp->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $allowedIp->user->email }}</div>
                                        @else
                                            <span class="text-gray-400">All Users</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                        {{ $allowedIp->ip_address }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $allowedIp->label ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        @if($allowedIp->user_id)
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                                User Specific
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                                Global
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if($allowedIp->is_active)
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
                                            <a href="{{ route('allowed-ips.edit', $allowedIp) }}"
                                               class="px-3 py-1.5 rounded-md bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('allowed-ips.destroy', $allowedIp) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this allowed IP?')">
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
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No allowed IP records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $allowedIps->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>