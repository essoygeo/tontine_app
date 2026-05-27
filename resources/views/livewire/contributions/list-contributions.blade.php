<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-extrabold text-white">Vos Transactions</h1>
        <p class="text-gray-200 mt-2 text-lg font-medium">Consultez et suivez l'historique de tous vos versements dans vos différentes tontines.</p>
    </div>

    <div class="bg-[#0e1319] border border-white/10 rounded-3xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">
                <thead class="text-xs text-gray-400 uppercase bg-black/20">
                    <tr>
                        <th class="px-6 py-4 font-bold">Référence</th>
                        <th class="px-6 py-4 font-bold">Tontine</th>
                        <th class="px-6 py-4 font-bold">Montant</th>
                        <th class="px-6 py-4 font-bold">Date</th>
                        <th class="px-6 py-4 font-bold text-center">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($contributions as $contribution)
                        <tr class="hover:bg-white/5 transition duration-150">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">
                                #TR-{{ str_pad($contribution->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('groups.show', $contribution->group) }}" class="font-bold text-white hover:text-amazon-orange transition">
                                    {{ $contribution->group->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-white">{{ number_format($contribution->amount, 0, ',', ' ') }}</span>
                                <span class="text-xs text-amazon-orange">FCFA</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-400">
                                {{ $contribution->paid_at ? $contribution->paid_at->format('d M Y') : $contribution->created_at->format('d M Y') }}
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
                            <td colspan="5" class="px-6 py-24 text-center text-gray-400">
                                Aucune transaction trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>