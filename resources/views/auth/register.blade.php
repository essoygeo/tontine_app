<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-3">
        @csrf

        <div class="mb-2">
            <h2 class="text-xl font-bold text-white">Créer un compte</h2>
            <p class="text-xs text-gray-400 mt-1">Rejoignez la communauté Kotize</p>
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-300">Nom complet</label>
            <input id="name" class="bg-black/50 border border-white/10 text-white text-sm rounded-xl focus:ring-amazon-orange focus:border-amazon-orange block w-full p-2.5 placeholder-gray-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-300">Email</label>
            <input id="email" class="bg-black/50 border border-white/10 text-white text-sm rounded-xl focus:ring-amazon-orange focus:border-amazon-orange block w-full p-2.5 placeholder-gray-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-300">Mot de passe</label>
            <input id="password" class="bg-black/50 border border-white/10 text-white text-sm rounded-xl focus:ring-amazon-orange focus:border-amazon-orange block w-full p-2.5 placeholder-gray-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-300">Confirmer le mot de passe</label>
            <input id="password_confirmation" class="bg-black/50 border border-white/10 text-white text-sm rounded-xl focus:ring-amazon-orange focus:border-amazon-orange block w-full p-2.5 placeholder-gray-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full text-amazon-dark bg-amazon-orange hover:bg-amazon-yellow font-bold rounded-xl text-sm px-5 py-3 transition shadow-lg shadow-amazon-orange/20">
                S'inscrire
            </button>
        </div>

        <div class="text-center text-sm text-gray-400 mt-4">
            Déjà inscrit ? <a href="{{ route('login') }}" wire:navigate class="text-amazon-orange font-bold hover:text-amazon-yellow transition">Se connecter</a>
        </div>
    </form>
</x-guest-layout>
