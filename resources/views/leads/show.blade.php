<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $lead->name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $lead->lead_code }}</p>
                    </div>

                    <a href="{{ route('leads.edit', $lead) }}"
                        class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                        Edit Lead
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $lead->email ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $lead->phone ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Company</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $lead->company ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Source</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $lead->source ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">
                            {{ ucwords(str_replace('_', ' ', $lead->status)) }}
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Priority</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ ucfirst($lead->priority) }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Assigned To</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">
                            {{ $lead->assignedEmployee->full_name ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Next Follow Up</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">
                            {{ $lead->next_follow_up_date ? $lead->next_follow_up_date->format('Y-m-d') : '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Last Contacted</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">
                            {{ $lead->last_contacted_at ? $lead->last_contacted_at->format('Y-m-d H:i') : '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-3">
                        <p class="text-sm text-gray-500">Subject</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $lead->subject ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-3">
                        <p class="text-sm text-gray-500">Message</p>
                        <p class="text-base text-gray-800 mt-1 whitespace-pre-line">{{ $lead->message ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div id="aiLeadInsights" class="space-y-4">
                @if(!empty($latestAiInsight?->output_payload))
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                            <p class="text-sm text-gray-500">Summary</p>
                            <p class="text-base text-gray-800 mt-1">{{ $latestAiInsight->output_payload['summary'] ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Lead Score</p>
                            <p class="text-3xl font-bold text-indigo-700 mt-1">
                                {{ $latestAiInsight->output_payload['score'] ?? 0 }}/100
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 md:col-span-3">
                            <p class="text-sm text-gray-500">Recommended Next Action</p>
                            <p class="text-base text-gray-800 mt-1">
                                {{ $latestAiInsight->output_payload['next_action'] ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <div class="rounded-lg bg-gray-50 text-gray-600 px-4 py-3">
                        Click "Generate Insights" to analyze this lead.
                    </div>
                @endif
            </div>
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">AI Lead Insights</h3>

                    <button type="button" onclick="loadLeadInsights()"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Generate Insights
                    </button>
                </div>

                <div id="aiLeadInsights" class="space-y-4">
                    <div class="rounded-lg bg-gray-50 text-gray-600 px-4 py-3">
                        Click "Generate Insights" to analyze this lead.
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Note</h3>

                <form method="POST" action="{{ route('leads.notes.store', $lead) }}" class="space-y-4">
                    @csrf
                    <div>
                        <textarea name="note" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Write follow-up note, call summary, next action..."></textarea>
                    </div>

                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Save Note
                    </button>
                </form>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Lead Notes</h3>

                <div class="space-y-4">
                    @forelse($lead->notes as $note)
                        <div class="border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $note->user->name ?? 'System' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $note->created_at->format('Y-m-d H:i') }}
                                </p>
                            </div>

                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $note->note }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No notes added yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <script>
        async function loadLeadInsights() {
            const container = document.getElementById('aiLeadInsights');

            container.innerHTML = `
            <div class="rounded-lg bg-blue-50 text-blue-700 px-4 py-3">
                Generating AI insights...
            </div>
        `;

            try {
                const response = await fetch("{{ route('ai.leads.insights', $lead) }}", {
                    headers: {
                        "Accept": "application/json"
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    container.innerHTML = `
                    <div class="rounded-lg bg-red-50 text-red-700 px-4 py-3">
                        Failed to generate AI insights.
                    </div>
                `;
                    return;
                }

                container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                        <p class="text-sm text-gray-500">Summary</p>
                        <p class="text-base text-gray-800 mt-1">${data.summary ?? '-'}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Lead Score</p>
                        <p class="text-3xl font-bold text-indigo-700 mt-1">${data.score ?? 0}/100</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-3">
                        <p class="text-sm text-gray-500">Recommended Next Action</p>
                        <p class="text-base text-gray-800 mt-1">${data.next_action ?? '-'}</p>
                    </div>
                </div>
            `;
            } catch (error) {
                container.innerHTML = `
                <div class="rounded-lg bg-red-50 text-red-700 px-4 py-3">
                    Something went wrong while generating insights.
                </div>
            `;
            }
        }
    </script>
</x-app-layout>