<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
                        <p class="text-sm text-gray-500 mt-1">All your system alerts and updates.</p>
                    </div>

                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Mark All as Read
                        </button>
                    </form>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-4">
                    @forelse($notifications as $notification)
                        <div
                            class="rounded-xl border {{ $notification->read_at ? 'border-gray-200 bg-white' : 'border-indigo-200 bg-indigo-50' }} p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <a href="{{ route('notifications.show', $notification) }}"
                                            class="text-base font-semibold text-gray-800 hover:text-indigo-600 transition">
                                            {{ $notification->title }}
                                        </a>

                                        @if(!$notification->read_at)
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">
                                                New
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-gray-700">
                                        {{ $notification->message ?? '-' }}
                                    </p>

                                    <div class="mt-3 text-xs text-gray-500 flex flex-wrap gap-3">
                                        <span>Type: {{ $notification->type }}</span>
                                        <span>{{ $notification->created_at->format('Y-m-d H:i') }}</span>
                                        @if($notification->read_at)
                                            <span>Read: {{ $notification->read_at->format('Y-m-d H:i') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2">
                                    @if(!$notification->read_at)
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                                                Mark Read
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('notifications.show', $notification) }}"
                                        class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm text-center">
                                        View
                                    </a>

                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}"
                                            class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm text-center">
                                            Open
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-gray-200 p-8 text-center text-sm text-gray-500">
                            No notifications found.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>