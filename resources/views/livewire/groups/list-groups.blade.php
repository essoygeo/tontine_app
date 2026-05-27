<div class="px-6 py-8">
    <!-- Notifications Section -->
    @php
        $allNotifications = $rejectedRequests->map(fn($g) => ['id' => $g->id, 'type' => 'rejected', 'message' => "Votre demande pour '{$g->name}' a été refusée."])
            ->concat($userGroups->where('pivot.is_notified', false)->map(fn($g) => ['id' => $g->id, 'type' => 'accepted', 'message' => "Vous avez été accepté dans '{$g->name}'."]));
    @endphp

    @if($allNotifications->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach($allNotifications as $notification)
                <div class="flex items-center justify-between p-4 rounded-2xl border {{ $notification['type'] === 'rejected' ? 'bg-red-900/30 border-red-700/50 text-red-200' : 'bg-green-900/30 border-green-700/50 text-green-200' }} shadow-sm">
                    <div class="flex items-center">
                        <span class="font-medium text-sm">{{ $notification['message'] }}</span>
                    </div>
                    <button wire:click="dismissNotification({{ $notification['id'] }})" class="p-1 hover:bg-white/10 rounded-lg transition" title="Fermer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-white">Mes Cercles de Tontine</h1>
            <p class="text-gray-200 mt-2 text-lg font-medium">Gérez vos participations et découvrez de nouvelles opportunités d'épargne.</p>
        </div>
        <button data-modal-target="create-group-modal" data-modal-toggle="create-group-modal" class="flex items-center justify-center px-6 py-4 text-sm font-bold text-white bg-amazon-orange rounded-2xl hover:bg-amazon-yellow transition shadow-xl transform active:scale-95">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
            Lancer une nouvelle tontine
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-6 mb-8 text-sm text-white bg-green-900/50 border border-green-700/50 rounded-2xl font-bold" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- SECTION: MES TONTINES ACTIVES -->
    <div class="mb-12">
        <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-wider">
            Mes participations actives
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($userGroups as $group)
                <div class="bg-[#0e1319] border border-white/10 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col">
                    <div class="p-8 flex-grow">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-16 h-16 rounded-2xl bg-black/20 flex items-center justify-center text-white text-2xl font-bold border border-white/5">
                                {{ substr($group->name, 0, 1) }}
                            </div>
                            <span class="bg-black/20 text-white text-[10px] font-bold px-4 py-2 rounded-full border border-white/5 uppercase tracking-tighter">
                                {{ ucfirst($group->pivot->role) }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ $group->name }}</h3>
                        <p class="text-gray-400 text-sm mb-6 leading-relaxed">{{ $group->description ?? 'Aucune description fournie.' }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/5">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Cotisation</p>
                                <p class="text-lg font-bold text-white">{{ number_format($group->cotisation_fixe, 0, ',', ' ') }} <span class="text-xs text-amazon-orange">FCFA</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Membres</p>
                                <p class="text-lg font-bold text-white">{{ $group->users_count }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('groups.show', $group) }}" class="block w-full text-center py-5 bg-white/5 text-xs font-bold text-white hover:bg-amazon-orange hover:text-black transition-colors uppercase tracking-widest">
                        Gérer la tontine
                    </a>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-[#0e1319] border-2 border-dashed border-white/5 rounded-3xl">
                    <p class="text-gray-400 font-bold">Vous n'avez pas encore de tontine active.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- SECTION: DECOUVRIR -->
    <div class="mb-12">
        <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-wider">
            Découvrir de nouveaux cercles
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($availableGroups as $group)
                <div class="bg-[#0e1319] border border-white/10 rounded-3xl shadow-xl overflow-hidden flex flex-col">
                    <div class="p-8 flex-grow">
                        <h3 class="text-xl font-bold text-white mb-2">{{ $group->name }}</h3>
                        <p class="text-gray-400 text-sm mb-6 leading-relaxed">{{ $group->description ?? "Ce groupe n'a pas encore de description." }}</p>
                        <div class="flex items-center justify-between pt-6 border-t border-white/5">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Cotisation</p>
                                <p class="text-lg font-bold text-white">{{ number_format($group->cotisation_fixe, 0, ',', ' ') }} <span class="text-xs text-amazon-orange">FCFA</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Membres</p>
                                <p class="text-lg font-bold text-white">{{ $group->users_count }}</p>
                            </div>
                        </div>
                    </div>
                    <button wire:click="joinRequest({{ $group->id }})" class="block w-full text-center py-5 bg-amazon-orange text-black text-xs font-bold hover:bg-amazon-yellow transition uppercase tracking-widest">
                        Rejoindre le cercle
                    </button>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <p class="text-gray-400 font-bold">Aucune nouvelle tontine disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Group Modal -->
    <div id="create-group-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full" wire:ignore.self>
        <div class="relative w-full max-w-lg max-h-full">
            <div class="relative bg-[#0e1319] rounded-3xl shadow-2xl border border-white/10 overflow-hidden">
                <div class="bg-black/20 p-8 border-b border-white/5">
                    <h3 class="text-xl font-bold text-white uppercase tracking-wider">Nouvelle Tontine</h3>
                </div>
                <div class="p-8">
                    <form class="space-y-6" wire:submit.prevent="createGroup">
                        <div>
                            <label class="block mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nom du Cercle</label>
                            <input type="text" wire:model="name" class="bg-black/20 border border-white/10 text-white text-sm rounded-2xl focus:ring-amazon-orange focus:border-amazon-orange block w-full p-4 transition" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Cotisation Fixe (FCFA)</label>
                            <input type="number" wire:model="cotisation_fixe" class="bg-black/20 border border-white/10 text-white text-sm rounded-2xl focus:ring-amazon-orange focus:border-amazon-orange block w-full p-4 transition" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Description</label>
                            <textarea wire:model="description" rows="3" class="bg-black/20 border border-white/10 text-white text-sm rounded-2xl focus:ring-amazon-orange focus:border-amazon-orange block w-full p-4 transition"></textarea>
                        </div>
                        <button type="submit" class="w-full text-black bg-amazon-orange hover:bg-amazon-yellow font-bold rounded-2xl text-sm px-5 py-5 transition uppercase tracking-widest">
                            Lancer le projet
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>