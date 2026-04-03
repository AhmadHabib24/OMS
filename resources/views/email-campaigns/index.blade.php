<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Email Campaigns</h2>
                        <p class="text-sm text-gray-500 mt-1">Manage email campaigns and sending logs.</p>
                    </div>

                    <a href="{{ route('email-campaigns.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Create Campaign
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Campaign</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Recipients</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sent</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Failed</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Created By</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($campaigns as $campaign)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $campaign->campaign_code }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $campaign->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($campaign->status) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $campaign->total_recipients }}</td>
                                    <td class="px-4 py-3 text-sm text-green-700">{{ $campaign->sent_count }}</td>
                                    <td class="px-4 py-3 text-sm text-red-700">{{ $campaign->failed_count }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $campaign->creator->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('email-campaigns.show', $campaign) }}"
                                               class="px-3 py-1.5 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200">
                                                View
                                            </a>

                                            @if($campaign->status === 'draft')
                                                <a href="{{ route('email-campaigns.edit', $campaign) }}"
                                                   class="px-3 py-1.5 rounded-md bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                                                    Edit
                                                </a>
                                            @endif

                                            <form action="{{ route('email-campaigns.destroy', $campaign) }}" method="POST"
                                                  onsubmit="return confirm('Delete this campaign?')">
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
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No campaigns found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $campaigns->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>