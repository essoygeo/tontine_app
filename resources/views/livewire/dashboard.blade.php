<div class="px-6 py-8">
    <!-- Notifications Section -->
    @if(count($notifications) > 0)
        <div class="space-y-4 mb-8">
            @foreach($notifications as $notification)
                <div class="flex items-center justify-between p-4 rounded-2xl border {{ $notification['type'] === 'rejected' ? 'bg-red-900/30 border-red-700/50 text-red-200' : 'bg-green-900/30 border-green-700/50 text-green-200' }} shadow-sm animate-pulse-once">
                    <div class="flex items-center">
                        @if($notification['type'] === 'rejected')
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
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

    <div class="mb-8">
        <h1 class="text-4xl font-extrabold text-white">Bonjour, <span class="text-white">{{ Auth::user()->name }}</span></h1>
        <p class="text-gray-200 mt-2 text-lg font-medium">Voici l'aperçu de votre activité d'épargne aujourd'hui.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card: Total Contributed -->
        <div class="p-6 bg-[#0e1319] border border-white/10 rounded-3xl shadow-xl flex items-center group">
            <div class="p-4 mr-5 text-amazon-dark bg-amazon-orange rounded-xl shadow-inner">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-1">Total Versé</p>
                <p class="text-3xl font-extrabold text-white">{{ number_format($totalContributed, 0, ',', ' ') }} <span class="text-xs text-amazon-orange">FCFA</span></p>
            </div>
        </div>

        <!-- Card: Incomplete -->
        <div class="p-6 bg-[#0e1319] border border-white/10 rounded-3xl shadow-xl flex items-center group">
            <div class="p-4 mr-5 text-amazon-dark bg-amazon-yellow rounded-xl shadow-inner">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-1">Échecs / Incomplets</p>
                <p class="text-3xl font-extrabold text-white">{{ number_format($incompleteAmount, 0, ',', ' ') }} <span class="text-xs text-amazon-orange">FCFA</span></p>
            </div>
        </div>

        <!-- Card: Groups Count -->
        <div class="p-6 bg-[#0e1319] border border-white/10 rounded-3xl shadow-xl flex items-center group">
            <div class="p-4 mr-5 text-white bg-[#0e1319] rounded-xl shadow-inner border border-white/10">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.005 3.005 0 013.75-2.906z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-1">Mes Cercles</p>
                <p class="text-3xl font-extrabold text-white">{{ $groups->count() }} <span class="text-xs text-gray-400">Actifs</span></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- My Groups Section -->
        <div class="lg:col-span-5 flex flex-col">
            <div class="bg-[#0e1319] border border-white/10 rounded-3xl shadow-xl overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-white/5 flex justify-between items-center bg-black/20">
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Mes Tontines</h3>
                    <a href="{{ route('groups.index') }}" class="text-sm font-bold text-amazon-orange hover:text-amazon-yellow transition uppercase tracking-widest">Voir tout</a>
                </div>
                <div class="p-4 space-y-2 flex-grow overflow-y-auto">
                    @forelse($groups as $group)
                        <div class="flex items-center justify-between p-4 bg-black/20 rounded-2xl border border-white/5 hover:border-amazon-orange/50 transition cursor-pointer" onclick="window.location='{{ route('groups.show', $group) }}'">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-2xl bg-black flex items-center justify-center text-amazon-orange font-bold mr-4 border border-white/10">
                                    {{ substr($group->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-white">{{ $group->name }}</h4>
                                    <p class="text-xs text-gray-400 font-medium">{{ $group->users_count }} membres • {{ number_format($group->cotisation_fixe, 0, ',', ' ') }} FCFA</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 px-6">
                            <p class="text-gray-400 font-medium italic text-sm">Aucun groupe actif.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Transactions Section -->
        <div class="lg:col-span-7 flex flex-col">
            <div class="bg-[#0e1319] border border-white/10 rounded-3xl shadow-xl overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-white/5 flex justify-between items-center bg-black/20">
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Activités Récentes</h3>
                    <a href="{{ route('contributions.index') }}" class="text-sm font-bold text-amazon-orange hover:text-amazon-yellow transition uppercase tracking-widest">Historique complet</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-black/20">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold">Groupe</th>
                                <th scope="col" class="px-6 py-4 font-bold text-right">Montant</th>
                                <th scope="col" class="px-6 py-4 font-bold text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($recentContributions as $contribution)
                                <tr class="hover:bg-white/5 transition duration-150 cursor-pointer" onclick="window.location='{{ route('contributions.index') }}'">
                                    <td class="px-6 py-4 text-white font-medium">{{ $contribution->group->name }}</td>
                                    <td class="px-6 py-4 text-right text-white font-bold">{{ number_format($contribution->amount, 0, ',', ' ') }} <span class="text-xs text-gray-400">FCFA</span></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-900/30 text-green-300 border border-green-700/50">Payé</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>