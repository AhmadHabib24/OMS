<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Email Campaign</h2>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('email-campaigns.store') }}" class="space-y-5">
                    @csrf
                    <div class="border border-indigo-200 bg-indigo-50 rounded-xl p-5 space-y-4">
                        <h3 class="text-lg font-semibold text-indigo-900">AI Campaign Generator</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Goal</label>
                                <input type="text" id="ai_goal"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Generate demo requests from real estate leads">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                                <input type="text" id="ai_audience"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Small business owners, ecommerce brands, property dealers">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tone</label>
                                <select id="ai_tone"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="professional">Professional</option>
                                    <option value="friendly">Friendly</option>
                                    <option value="persuasive">Persuasive</option>
                                    <option value="formal">Formal</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Offer / Service</label>
                                <input type="text" id="ai_offer"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Laravel development, CRM solution, AI email automation">
                            </div>
                        </div>

                        <div>
                            <button type="button" onclick="generateAIContent()"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Generate with AI
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Body</label>
                        <textarea name="body" rows="10"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Use placeholders like {name}, {email}, {company}">{{ old('body') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Select Leads</label>

                        <div class="max-h-80 overflow-y-auto border border-gray-200 rounded-xl p-4 space-y-3">
                            @foreach($leads as $lead)
                                <label class="flex items-start gap-3">
                                    <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}"
                                        class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $lead->name }} ({{ $lead->email }})
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $lead->company ?? 'No Company' }} |
                                            {{ ucwords(str_replace('_', ' ', $lead->status)) }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Save Campaign
                        </button>

                        <a href="{{ route('email-campaigns.index') }}"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        async function generateAIContent() {
            const goal = document.getElementById('ai_goal').value;
            const audience = document.getElementById('ai_audience').value;
            const tone = document.getElementById('ai_tone').value;
            const offer = document.getElementById('ai_offer').value;

            if (!goal || !audience || !tone || !offer) {
                alert('Please fill all AI generator fields first.');
                return;
            }

            try {
                const response = await fetch("{{ route('ai.generate-campaign-content') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ goal, audience, tone, offer })
                });

                const data = await response.json();

                if (!response.ok) {
                    alert('AI generation failed.');
                    return;
                }

                document.querySelector('input[name="subject"]').value = data.subject ?? '';
                document.querySelector('textarea[name="body"]').value = data.body ?? '';
            } catch (error) {
                alert('Something went wrong while generating AI content.');
            }
        }
    </script>
</x-app-layout>