<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $campaign->name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $campaign->campaign_code }}</p>
                    </div>

                    <div class="flex gap-3">
                        @if($campaign->status === 'draft')
                            <a href="{{ route('email-campaigns.edit', $campaign) }}"
                               class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('email-campaigns.send', $campaign) }}"
                                  onsubmit="return confirm('Send this campaign now?')">
                                @csrf
                                <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                    Send Campaign
                                </button>
                            </form>
                        @endif
                    </div>
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

                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ ucfirst($campaign->status) }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Recipients</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $campaign->total_recipients }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Sent</p>
                        <p class="text-base font-semibold text-green-700 mt-1">{{ $campaign->sent_count }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Failed</p>
                        <p class="text-base font-semibold text-red-700 mt-1">{{ $campaign->failed_count }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-4">
                        <p class="text-sm text-gray-500">Subject</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $campaign->subject }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-4">
                        <p class="text-sm text-gray-500">Body</p>
                        <p class="text-base text-gray-800 mt-1 whitespace-pre-line">{{ $campaign->body }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recipient Logs</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Recipient</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Lead</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sent At</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Error</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($campaign->logs as $log)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $log->recipient_name ?? '-' }}<br>
                                        <span class="text-xs text-gray-500">{{ $log->recipient_email }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $log->lead->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($log->status) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-red-700">{{ $log->error_message ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>