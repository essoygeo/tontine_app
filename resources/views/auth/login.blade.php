<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div class="mb-4">
            <h2 class="text-2xl font-bold text-white">Bienvenue</h2>
            <p class="text-sm text-gray-400 mt-1">Connectez-vous pour continuer</p>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-300">Email</label>
            <input id="email" class="bg-black/50 border border-white/10 text-white text-sm rounded-xl focus:ring-amazon-orange focus:border-amazon-orange block w-full p-3 placeholder-gray-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-300">Mot de passe</label>
            <input id="password" class="bg-black/50 border border-white/10 text-white text-sm rounded-xl focus:ring-amazon-orange focus:border-amazon-orange block w-full p-3 placeholder-gray-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-black border-white/10 text-amazon-orange shadow-sm focus:ring-amazon-orange focus:ring-offset-black" name="remember">
                <span class="ms-2 text-sm text-gray-400">Se souvenir de moi</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-amazon-orange hover:text-amazon-yellow transition font-medium" href="{{ route('password.request') }}" wire:navigate>
                    Oublié ?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full text-amazon-dark bg-amazon-orange hover:bg-amazon-yellow font-bold rounded-xl text-sm px-5 py-4 transition shadow-lg shadow-amazon-orange/20">
                Se connecter
            </button>
        </div>

        <div class="text-center text-sm text-gray-400">
            Pas encore de compte ? <a href="{{ route('register') }}" wire:navigate class="text-amazon-orange font-bold hover:text-amazon-yellow transition">S'inscrire</a>
        </div>
    </form>
</x-guest-layout>
