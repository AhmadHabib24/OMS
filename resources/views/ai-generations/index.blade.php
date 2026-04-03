<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">AI Generation History</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Model</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Lead</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tokens</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($generations as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->type }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->model }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->lead->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($item->status) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->total_tokens ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No AI generation logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $generations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>