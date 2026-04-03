<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $notification->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $notification->created_at->format('Y-m-d H:i') }}
                        </p>
                    </div>

                    @if($notification->read_at)
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            Read
                        </span>
                    @else
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">
                            New
                        </span>
                    @endif
                </div>

                <div class="space-y-5">
                    <div class="rounded-xl bg-gray-50 p-5">
                        <p class="text-sm text-gray-500 mb-2">Type</p>
                        <p class="text-base font-medium text-gray-800">{{ $notification->type }}</p>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-5">
                        <p class="text-sm text-gray-500 mb-2">Message</p>
                        <p class="text-base text-gray-800 whitespace-pre-line">
                            {{ $notification->message ?? '-' }}
                        </p>
                    </div>

                    @if(!empty($notification->meta))
                        <div class="rounded-xl bg-gray-50 p-5">
                            <p class="text-sm text-gray-500 mb-3">Meta Info</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($notification->meta as $key => $value)
                                    <div class="text-sm">
                                        <span class="font-medium text-gray-700">{{ str_replace('_', ' ', ucfirst($key)) }}:</span>
                                        <span class="text-gray-600">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    @if($notification->action_url)
                        <a href="{{ $notification->action_url }}"
                           class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Open Related Page
                        </a>
                    @endif

                    <a href="{{ route('notifications.index') }}"
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        Back to Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>