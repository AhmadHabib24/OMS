<x-guest-layout>
    <div class=""
         style="background: linear-gradient(135deg, #0B1533, #1B2A57, #2E3F7A);">

        <div class="w-full max-w-md bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8">

            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/tecveq.png') }}" class="h-16" alt="TECVEQ">
            </div>

            <!-- Title -->
            <h2 class="text-center text-2xl font-bold text-[#0B1533] mb-2">
                Welcome Back
            </h2>
            <p class="text-center text-sm text-gray-500 mb-6">
                Login to Office Management System
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-green-600" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" value="Email" class="text-[#1B2A57]" />
                    <x-text-input id="email"
                        class="block mt-1 w-full border-gray-300 focus:border-[#2E3F7A] focus:ring-[#2E3F7A] rounded-lg"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" value="Password" class="text-[#1B2A57]" />
                    <x-text-input id="password"
                        class="block mt-1 w-full border-gray-300 focus:border-[#2E3F7A] focus:ring-[#2E3F7A] rounded-lg"
                        type="password"
                        name="password"
                        required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between mt-4">
                    <label class="flex items-center">
                        <input type="checkbox"
                            class="rounded border-gray-300 text-[#2E3F7A] focus:ring-[#2E3F7A]"
                            name="remember">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-[#2E3F7A] hover:underline"
                           href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <div class="mt-6">
                    <button type="submit"
                        class="w-full py-3 rounded-lg text-white font-semibold transition"
                        style="background: linear-gradient(135deg, #0B1533, #2E3F7A);">
                        Log In
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>