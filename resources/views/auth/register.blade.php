<x-app-layout>
    <form method="POST" action="{{ route('register') }}" class="w-[400px] mx-auto p-6 my-16"
    >
        @csrf

        <h2 class="text-2xl font-semibold text-center mb-4">Create an account</h2>
        <p class="text-center text-gray-500 mb-3">
            or
            <a
                href="{{ route('login') }}"
                class="text-sm text-purple-700 hover:text-purple-600"
            >
                login with existing account
            </a>
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        <div class="mb-4">
            <x-input
                type="text"
                name="name"
                placeholder="Your name"
                :value="old('name')"
            />
        </div>
        <div class="mb-4">
            <x-input
                type="email"
                name="email"
                placeholder="Your Email"
                :value="old('email')"
            />
        </div>
        <div class="mb-4">
            <x-input
                type="password"
                name="password"
                placeholder="Password"
            />
        </div>
        <div class="mb-4">
            <x-input
                type="password"
                name="password_confirmation"
                placeholder="Repeat Password"
            />
        </div>

        <button
            class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full"
        >
            Signup
        </button>
    </form>
</x-app-layout>
