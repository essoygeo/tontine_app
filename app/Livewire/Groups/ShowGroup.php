<?php

namespace App\Livewire\Groups;

use Livewire\Component;
use App\Models\Group;
use App\Models\Contribution;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ShowGroup extends Component
{
    public Group $group;
    public $amount;
    public $selected_user_id;

    public $name;
    public $description;
    public $cotisation_fixe;

    public function mount(Group $group)
    {
        $this->group = $group->load(['users', 'contributions' => function($query) {
            $query->where('status', 'paid')->with('user')->latest();
        }]);
        $this->amount = $group->cotisation_fixe;
        
        // Initialiser les champs d'édition
        $this->name = $group->name;
        $this->description = $group->description;
        $this->cotisation_fixe = $group->cotisation_fixe;
    }

    public function updateGroup()
    {
        if (!$this->isAdmin()) {
            session()->flash('error', 'Action non autorisée.');
            return;
        }

        $this->validate([
            'name' => 'required|string|min:3',
            'cotisation_fixe' => 'required|numeric|min:100',
            'description' => 'nullable|string',
        ]);

        $this->group->update([
            'name' => $this->name,
            'description' => $this->description,
            'cotisation_fixe' => $this->cotisation_fixe,
        ]);

        $this->dispatch('close-modal', 'edit-group-modal');
        session()->flash('message', 'Le cercle a été mis à jour avec succès.');
    }

    public function deleteGroup()
    {
        try {
            if (!$this->isAdmin()) {
                \Illuminate\Support\Facades\Log::warning('Tentative de suppression de groupe sans être admin', ['user_id' => Auth::id(), 'group_id' => $this->group->id]);
                session()->flash('error', 'Action non autorisée.');
                return;
            }

            \Illuminate\Support\Facades\Log::info('Suppression du groupe en cours', ['group_id' => $this->group->id]);

            // Detach users manually because the migration doesn't have cascade delete on group_user
            $this->group->users()->detach();
            
            // Now delete the group (contributions should cascade if the DB supports it, 
            // but we can also handle them manually if needed)
            $this->group->delete();
            
            session()->flash('message', 'Le cercle a été supprimé définitivement.');
            return redirect()->route('groups.index');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de la suppression du groupe', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Impossible de supprimer le cercle : ' . $e->getMessage());
        }
    }

    public function addContribution()
    {
        $groupId = $this->group->id; // ou propriété livewire
        $amount = $this->amount;
        return redirect()->route('payment',['groupId' => $groupId,
            'amount' => $amount]);
    }

    public function markAsPaid($contributionId)
    {
        if (!$this->isAdmin()) {
            session()->flash('error', 'Action non autorisée.');
            return;
        }

        $contribution = Contribution::find($contributionId);
        $contribution->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->refreshGroup();
        session()->flash('message', 'Paiement confirmé !');
    }

    public function approveMember($userId)
    {
        if (!$this->isAdmin()) {
            session()->flash('error', 'Action non autorisée.');
            return;
        }

        try {
            \Illuminate\Support\Facades\Log::info('Tentative d\'approbation du membre', ['user_id' => $userId, 'group_id' => $this->group->id]);
            $this->group->users()->updateExistingPivot($userId, ['status' => 'active', 'is_notified' => false]);
            $this->refreshGroup();
            session()->flash('message', 'Membre approuvé !');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de l\'approbation', ['message' => $e->getMessage()]);
            session()->flash('error', 'Erreur lors de l\'approbation.');
        }
    }

    public function rejectMember($userId)
    {
        if (!$this->isAdmin()) {
            session()->flash('error', 'Action non autorisée.');
            return;
        }

        try {
            \Illuminate\Support\Facades\Log::info('Tentative de refus du membre', ['user_id' => $userId, 'group_id' => $this->group->id]);
            $this->group->users()->updateExistingPivot($userId, ['status' => 'rejected', 'is_notified' => false]);
            $this->refreshGroup();
            session()->flash('message', 'La demande a été refusée.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors du refus', ['message' => $e->getMessage()]);
            session()->flash('error', 'Erreur lors du refus.');
        }
    }

    private function isAdmin()
    {
        return $this->group->users()
            ->where('user_id', Auth::id())
            ->wherePivot('role', 'admin')
            ->exists();
    }

    public function leaveGroup()
    {
        $userId = Auth::id();
        
        // Vérifier si l'utilisateur est bien membre
        if ($this->group->users()->where('user_id', $userId)->exists()) {
            $this->group->users()->detach($userId);
            session()->flash('message', "Vous avez quitté le cercle '{$this->group->name}'.");
            return redirect()->route('groups.index');
        }
    }

    public function startRotation()
    {
        if (!$this->isAdmin()) {
            session()->flash('error', 'Action non autorisée.');
            return;
        }

        $members = $this->group->users()->wherePivot('status', 'active')->get()->shuffle();
        
        foreach ($members as $index => $member) {
            $this->group->users()->updateExistingPivot($member->id, ['turn_order' => $index + 1]);
        }

        $this->group->update(['current_turn' => 1, 'turn_status' => 'collecting']);
        $this->refreshGroup();
        session()->flash('message', 'La rotation a été lancée avec succès !');
    }

    public function nextTurn()
    {
        if (!$this->isAdmin()) {
            session()->flash('error', 'Action non autorisée.');
            return;
        }

        // Vérifier si tout le monde a payé pour le tour actuel
        $activeMembersCount = $this->group->users()->wherePivot('status', 'active')->count();
        $paidContributionsCount = $this->group->contributions()
            ->where('turn_number', $this->group->current_turn)
            ->where('status', 'paid')
            ->count();

        if ($paidContributionsCount < $activeMembersCount) {
            session()->flash('error', "Tous les membres n'ont pas encore cotisé pour ce tour.");
            return;
        }

        if ($this->group->current_turn >= $activeMembersCount) {
            $this->group->update(['turn_status' => 'completed']);
            session()->flash('message', 'La tontine est terminée ! Tous les tours ont été effectués.');
        } else {
            $this->group->increment('current_turn');
            session()->flash('message', "Passage au tour {$this->group->current_turn}.");
        }
        
        $this->refreshGroup();
    }

    public function refreshGroup()
    {
        $this->group->load(['users', 'contributions' => function($query) {
            $query->where('status', 'paid')->with('user')->latest();
        }]);
    }

    public function render()
    {
        $userId = Auth::id();
        $isAdmin = $this->group->users()->where('user_id', $userId)->wherePivot('role', 'admin')->exists();
        \Illuminate\Support\Facades\Log::info('Rendu de ShowGroup', [
            'group_id' => $this->group->id,
            'is_admin' => $isAdmin,
            'user_id' => $userId
        ]);
        
        $totalCollected = $this->group->contributions()->where('status', 'paid')->sum('amount');
        $isMember = $this->group->users()->where('user_id', $userId)->wherePivot('status', 'active')->exists();
        
        $hasPaidCurrentTurn = $this->group->contributions()
            ->where('user_id', $userId)
            ->where('turn_number', $this->group->current_turn)
            ->where('status', 'paid')
            ->exists();

        $pendingMembers = $this->group->users()->wherePivot('status', 'pending')->get();
        $activeMembers = $this->group->users()->wherePivot('status', 'active')->get();
        
        // Calcul des informations du tour actuel
        $currentBeneficiary = $this->group->users()
            ->wherePivot('turn_order', $this->group->current_turn)
            ->first();
            
        $turnContributions = $this->group->contributions()
            ->where('turn_number', $this->group->current_turn)
            ->where('status', 'paid')
            ->get();

        return view('livewire.groups.show-group', [
            'totalCollected' => $totalCollected,
            'isAdmin' => $isAdmin,
            'isMember' => $isMember,
            'hasPaidCurrentTurn' => $hasPaidCurrentTurn,
            'pendingMembers' => $pendingMembers,
            'activeMembers' => $activeMembers,
            'currentBeneficiary' => $currentBeneficiary,
            'turnContributions' => $turnContributions,
        ])->layout('layouts.app');
    }
}
