<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800">My Leave Balance</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Yearly leave balance summary for {{ now()->year }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                @foreach($balances as $balance)
                    <div class="bg-white shadow rounded-xl p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">{{ $balance['name'] }}</p>
                                <h3 class="text-lg font-bold text-gray-800 mt-1">{{ $balance['code'] }}</h3>
                            </div>

                            @if($balance['is_paid'])
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    Paid
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                    Unpaid
                                </span>
                            @endif
                        </div>

                        <div class="mt-5 space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Allowed</span>
                                <span class="font-semibold text-gray-800">{{ $balance['allowed_days'] }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Used</span>
                                <span class="font-semibold text-red-600">{{ $balance['used_days'] }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Remaining</span>
                                <span class="font-semibold text-green-600">{{ $balance['remaining_days'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <a href="{{ route('leave-requests.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Apply Leave
                </a>
            </div>
        </div>
    </div>
</x-app-layout>