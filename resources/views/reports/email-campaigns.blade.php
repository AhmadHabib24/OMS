<x-app-layout>
    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex flex-col gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Email Campaign Reports</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Performance report for email campaigns and recipient logs.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('reports.email-campaigns') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">From</label>
                            <input type="date" name="from" value="{{ request('from', $from->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">To</label>
                            <input type="date" name="to" value="{{ request('to', $to->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Status</label>
                            <select name="status"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sending" {{ request('status') === 'sending' ? 'selected' : '' }}>Sending</option>
                                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Code, campaign name, subject..."
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Apply
                            </button>

                            <a href="{{ route('reports.email-campaigns') }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                Reset
                            </a>
                            <a href="{{ route('reports.email-campaigns.export', request()->query()) }}"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                    Export CSV
                                </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Total Campaigns</p>
                    <h4 class="text-3xl font-bold text-gray-800 mt-2">{{ $summary['total_campaigns'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Draft</p>
                    <h4 class="text-3xl font-bold text-yellow-600 mt-2">{{ $summary['draft'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Sent</p>
                    <h4 class="text-3xl font-bold text-green-600 mt-2">{{ $summary['sent'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Failed</p>
                    <h4 class="text-3xl font-bold text-red-600 mt-2">{{ $summary['failed'] }}</h4>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Sending</p>
                    <h4 class="text-2xl font-bold text-blue-600 mt-2">{{ $summary['sending'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Total Recipients</p>
                    <h4 class="text-2xl font-bold text-gray-800 mt-2">{{ $summary['total_recipients'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Total Sent</p>
                    <h4 class="text-2xl font-bold text-green-600 mt-2">{{ $summary['total_sent'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Total Failed</p>
                    <h4 class="text-2xl font-bold text-red-600 mt-2">{{ $summary['total_failed'] }}</h4>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Pending Logs</p>
                    <h4 class="text-2xl font-bold text-yellow-600 mt-2">{{ $logSummary['pending_logs'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Sent Logs</p>
                    <h4 class="text-2xl font-bold text-green-600 mt-2">{{ $logSummary['sent_logs'] }}</h4>
                </div>

                <div class="bg-white shadow rounded-xl p-5">
                    <p class="text-sm text-gray-500">Failed Logs</p>
                    <h4 class="text-2xl font-bold text-red-600 mt-2">{{ $logSummary['failed_logs'] }}</h4>
                </div>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Campaign Performance</h3>

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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sent At</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($records as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $record->campaign_code }}</td>

                                    <td class="px-4 py-3 text-sm">
                                        <div class="font-medium text-gray-800">{{ $record->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $record->subject }}</div>
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if($record->status === 'draft')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Draft
                                            </span>
                                        @elseif($record->status === 'sending')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                                Sending
                                            </span>
                                        @elseif($record->status === 'sent')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Sent
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                Failed
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $record->total_recipients }}</td>
                                    <td class="px-4 py-3 text-sm text-green-700 font-medium">{{ $record->sent_count }}</td>
                                    <td class="px-4 py-3 text-sm text-red-700 font-medium">{{ $record->failed_count }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $record->creator->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $record->sent_at ? $record->sent_at->format('Y-m-d H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No campaign records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $records->links() }}
                </div>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Recipient Logs</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Campaign</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Recipient</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Lead</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sent At</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Error</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($recentLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $log->campaign->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $log->recipient_name ?? '-' }}<br>
                                        <span class="text-xs text-gray-500">{{ $log->recipient_email }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $log->lead->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($log->status === 'sent')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Sent
                                            </span>
                                        @elseif($log->status === 'failed')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                Failed
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-red-700 max-w-xs">
                                        {{ $log->error_message ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No recipient logs found.
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