<x-guest-layout>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-[#0B1533]">Create Account</h2>
        <p class="text-sm text-gray-500 mt-1">
            Join Office Management System
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Full Name" class="text-[#1B2A57]" />
            <x-text-input id="name"
                class="block mt-1 w-full border-gray-300 focus:border-[#2E3F7A] focus:ring-[#2E3F7A] rounded-lg"
                type="text"
                name="name"
                :value="old('name')"
                required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email Address" class="text-[#1B2A57]" />
            <x-text-input id="email"
                class="block mt-1 w-full border-gray-300 focus:border-[#2E3F7A] focus:ring-[#2E3F7A] rounded-lg"
                type="email"
                name="email"
                :value="old('email')"
                required />
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

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirm Password" class="text-[#1B2A57]" />
            <x-text-input id="password_confirmation"
                class="block mt-1 w-full border-gray-300 focus:border-[#2E3F7A] focus:ring-[#2E3F7A] rounded-lg"
                type="password"
                name="password_confirmation"
                required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-6">

            <a href="{{ route('login') }}"
               class="text-sm text-[#2E3F7A] hover:underline">
                Already have an account?
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-lg text-white font-semibold transition"
                style="background: linear-gradient(135deg, #0B1533, #2E3F7A);">
                Register
            </button>

        </div>
    </form>

</x-guest-layout>