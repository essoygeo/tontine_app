<div id="show-group-component" class="px-6 py-8">
    <!-- Group Header Section -->
    <div class="mb-8">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm font-medium">
                <li class="inline-flex items-center">
                    <a href="{{ route('groups.index') }}" class="text-gray-400 hover:text-amazon-orange transition">Mes Tontines</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-600 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-white font-bold">{{ $group->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-4xl font-extrabold text-white">{{ $group->name }}</h1>
                    @if($isAdmin)
                        <button data-modal-target="edit-group-modal" data-modal-toggle="edit-group-modal" class="p-2 text-gray-400 hover:text-amazon-orange transition rounded-lg hover:bg-white/5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button wire:click="deleteGroup" wire:confirm="Êtes-vous sûr de vouloir SUPPRIMER ce cercle ? Cette action est irréversible." class="p-2 text-gray-400 hover:text-red-500 transition rounded-lg hover:bg-white/5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    @endif
                </div>
                <p class="text-lg text-gray-300 mt-2">{{ $group->description }}</p>
            </div>
            <div class="bg-[#0e1319] p-6 rounded-3xl border border-amazon-orange shadow-xl min-w-[200px]">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Collecté</p>
                <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalCollected, 0, ',', ' ') }} <span class="text-sm text-amazon-orange">FCFA</span></p>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-6 mb-8 text-sm text-white bg-green-900/50 border border-green-700/50 rounded-2xl font-bold" role="alert">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-6 mb-8 text-sm text-white bg-red-900/50 border border-red-700/50 rounded-2xl font-bold" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Turn Management Section -->
        <div class="lg:col-span-12">
            <div class="bg-[#0e1319] rounded-3xl shadow-xl border border-white/10 overflow-hidden mb-8">
                <div class="p-6 bg-black/20 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-white uppercase tracking-wider">Rotation & Gestion des Tours</h3>
                        <p class="text-sm text-gray-400">Suivi des cycles de distribution du pot.</p>
                    </div>
                    <div class="flex gap-3">
                        @if($isAdmin && $group->turn_status === 'collecting' && $activeMembers->where('pivot.turn_order', null)->count() > 0)
                            <button wire:click="startRotation" class="bg-amazon-orange text-black px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-amazon-yellow transition shadow-lg">Lancer la rotation</button>
                        @endif
                        
                        @if($isAdmin && $group->turn_status === 'collecting' && $currentBeneficiary)
                            <button wire:click="nextTurn" class="bg-white/5 hover:bg-white/10 text-white border border-white/10 px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest transition">Passer au tour suivant</button>
                        @endif
                    </div>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-black/20 p-4 rounded-2xl border border-white/5">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Tour Actuel</p>
                        <p class="text-2xl font-extrabold text-white">{{ $group->current_turn }} / {{ $activeMembers->count() }}</p>
                    </div>
                    <div class="bg-black/20 p-4 rounded-2xl border border-white/5">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Bénéficiaire</p>
                        <p class="text-lg font-bold text-amazon-orange">{{ $currentBeneficiary ? $currentBeneficiary->name : 'Non défini' }}</p>
                    </div>
                    <div class="bg-black/20 p-4 rounded-2xl border border-white/5">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Cotisations Reçues</p>
                        <p class="text-2xl font-extrabold text-white">{{ $turnContributions->count() }} / {{ $activeMembers->count() }}</p>
                    </div>
                    <div class="bg-black/20 p-4 rounded-2xl border border-white/5">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Pot du Tour</p>
                        <p class="text-2xl font-extrabold text-green-400">{{ number_format($turnContributions->sum('amount'), 0, ',', ' ') }} <span class="text-xs">FCFA</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Column: Transactions (Top) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-[#0e1319] rounded-3xl shadow-xl border border-white/10 overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="text-xl font-bold text-white">Historique des versements</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-black/20">
                            <tr>
                                <th class="px-6 py-4 font-bold">Tour</th>
                                <th class="px-6 py-4 font-bold">Membre</th>
                                <th class="px-6 py-4 font-bold text-right">Montant</th>
                                <th class="px-6 py-4 font-bold">Date</th>
                                <th class="px-6 py-4 font-bold text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($group->contributions as $contribution)
                                <tr class="hover:bg-white/5 transition duration-150 {{ $contribution->turn_number == $group->current_turn ? 'bg-amazon-orange/5' : '' }}">
                                    <td class="px-6 py-4 text-center font-bold text-amazon-orange">#{{ $contribution->turn_number }}</td>
                                    <td class="px-6 py-4 text-white font-medium">{{ $contribution->user->name }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-white">{{ number_format($contribution->amount, 0, ',', ' ') }}</span>
                                        <span class="text-xs text-amazon-orange ml-1">FCFA</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-400">
                                        {{ $contribution->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($contribution->status === 'paid')
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-900/30 text-green-300 border border-green-700/50 uppercase tracking-widest">
                                                Payé
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amazon-orange/20 text-amazon-orange border border-amazon-orange/40 uppercase tracking-widest">
                                                En attente
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                        Aucun versement enregistré.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Payment Action Card -->
            @if($isMember && $group->turn_status === 'collecting')
                <div class="bg-[#0e1319] rounded-3xl shadow-xl border border-white/10 overflow-hidden">
                    <div class="p-6 border-b border-white/5">
                        <h3 class="text-lg font-bold text-white">Effectuer un paiement</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <p class="text-xs text-gray-400 mb-1 uppercase tracking-widest font-bold">Cotisation du tour #{{ $group->current_turn }}</p>
                            <p class="text-2xl font-bold text-white">{{ number_format($group->cotisation_fixe, 0, ',', ' ') }} <span class="text-xs text-amazon-orange">FCFA</span></p>
                        </div>
                        
                        @if($hasPaidCurrentTurn)
                            <div class="p-5 bg-black/20 border border-emerald-500/20 rounded-2xl flex items-center group transition-all duration-300">
                                <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-500 shadow-inner mr-4 group-hover:scale-110 transition-transform border border-emerald-500/20">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-0.5">Cotisation Validée</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Tour #{{ $group->current_turn }} • Merci !</p>
                                </div>
                            </div>
                        @else
                            <form wire:submit.prevent="addContribution" class="space-y-4">
                                <button type="submit" class="w-full bg-amazon-orange hover:bg-amazon-yellow text-amazon-dark font-bold py-4 px-4 rounded-2xl transition duration-200 shadow-xl">
                                    Payer maintenant
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Admin Approval Card -->
            @if($isAdmin && $pendingMembers->count() > 0)
                <div class="bg-[#0e1319] rounded-3xl shadow-xl border border-amazon-orange/30 overflow-hidden">
                    <div class="p-6 border-b border-amazon-orange/20 bg-amazon-orange/10">
                        <h3 class="text-lg font-bold text-white">Demandes en attente</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach($pendingMembers as $member)
                            <div wire:key="pending-member-{{ $member->id }}" class="bg-black/20 p-4 rounded-2xl border border-white/5">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-amazon-orange flex items-center justify-center text-amazon-dark font-bold mr-3">{{ substr($member->name, 0, 1) }}</div>
                                    <div>
                                        <p class="font-bold text-sm text-white">{{ $member->name }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <button wire:click="approveMember({{ $member->id }})" wire:loading.attr="disabled" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 rounded-lg transition disabled:opacity-50">
                                        <span wire:loading.remove wire:target="approveMember({{ $member->id }})">Approuver</span>
                                        <span wire:loading wire:target="approveMember({{ $member->id }})">...</span>
                                    </button>
                                    <button wire:click="rejectMember({{ $member->id }})" wire:loading.attr="disabled" class="bg-white/10 hover:bg-white/20 text-white text-xs font-bold py-2 rounded-lg transition disabled:opacity-50">
                                        <span wire:loading.remove wire:target="rejectMember({{ $member->id }})">Refuser</span>
                                        <span wire:loading wire:target="rejectMember({{ $member->id }})">...</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Members List Card -->
            <div class="bg-[#0e1319] rounded-3xl shadow-xl border border-white/10 overflow-hidden">
                <div class="p-6 border-b border-white/5 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Membres ({{ $activeMembers->count() }})</h3>
                </div>
                <div class="p-2 max-h-[400px] overflow-y-auto">
                    @foreach($activeMembers as $user)
                        <div class="flex items-center p-4 hover:bg-white/5 rounded-2xl transition cursor-default">
                            <div class="w-12 h-12 rounded-2xl bg-black/20 flex items-center justify-center text-amazon-orange font-bold mr-4 border border-white/5 relative">
                                {{ substr($user->name, 0, 1) }}
                                @if($user->pivot->turn_order)
                                    <span class="absolute -top-1 -right-1 bg-amazon-orange text-black text-[8px] font-black w-4 h-4 rounded-full flex items-center justify-center border border-black">{{ $user->pivot->turn_order }}</span>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-sm text-white">{{ $user->name }}</p>
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest">{{ $user->pivot->role === 'admin' ? 'Administrateur' : 'Membre' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($isMember && !$isAdmin)
                    <div class="p-4 border-t border-white/5 bg-black/10">
                        <button wire:click="leaveGroup" wire:confirm="Êtes-vous sûr de vouloir quitter ce cercle ?" class="w-full flex items-center justify-center py-3 px-4 text-[10px] font-bold text-red-400 hover:bg-red-900/20 rounded-xl border border-red-900/30 transition-all duration-200 uppercase tracking-widest">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Quitter le cercle
                        </button>
                    </div>
                @endif
            </div>
        </div>
    <!-- Edit Group Modal -->
    @if($isAdmin)
    <div id="edit-group-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full" wire:ignore.self>
        <div class="relative w-full max-w-lg max-h-full">
            <div class="relative bg-[#0e1319] rounded-3xl shadow-2xl border border-white/10 overflow-hidden">
                <div class="bg-black/20 p-8 border-b border-white/5">
                    <h3 class="text-xl font-bold text-white uppercase tracking-wider">Modifier le Cercle</h3>
                </div>
                <div class="p-8">
                    <form class="space-y-6" wire:submit.prevent="updateGroup">
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
                        <div class="flex gap-4">
                            <button type="submit" class="flex-grow text-black bg-amazon-orange hover:bg-amazon-yellow font-bold rounded-2xl text-sm px-5 py-4 transition uppercase tracking-widest">
                                Enregistrer
                            </button>
                            <button type="button" data-modal-hide="edit-group-modal" class="px-6 py-4 text-xs font-bold text-white bg-white/5 hover:bg-white/10 rounded-2xl transition uppercase tracking-widest">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>