<?php

namespace App\Livewire\Groups;

use Livewire\Component;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class ListGroups extends Component
{
    public $name;
    public $cotisation_fixe;
    public $description;

    protected $rules = [
        'name' => 'required|string|min:3',
        'cotisation_fixe' => 'required|numeric|min:100',
        'description' => 'nullable|string',
    ];

    public function createGroup()
    {
        $this->validate();

        $group = Group::create([
            'name' => $this->name,
            'cotisation_fixe' => $this->cotisation_fixe,
            'description' => $this->description,
            'creator_id' => Auth::id(),
        ]);

        $group->users()->attach(Auth::id(), ['role' => 'admin', 'status' => 'active']);

        $this->reset(['name', 'cotisation_fixe', 'description']);
        $this->dispatch('close-modal', 'create-group-modal');
        session()->flash('message', 'Groupe créé avec succès !');
    }

    public function joinRequest($groupId)
    {
        $group = Group::findOrFail($groupId);
        
        if (!$group->users()->where('user_id', Auth::id())->exists()) {
            $group->users()->attach(Auth::id(), ['role' => 'member', 'status' => 'pending']);
            session()->flash('message', "Votre demande pour rejoindre {$group->name} a été envoyée.");
        }
    }

    public function render()
    {
        // Correction ici : Utilisation de wherePivot pour la requête principale 
        // et where sur la table pivot pour le count
        $userGroups = Auth::user()->groups()
            ->wherePivot('status', 'active')
            ->withCount(['users as users_count' => function($query) {
                $query->where('group_user.status', 'active');
            }])
            ->get();
        
        $availableGroups = Group::whereDoesntHave('users', function($query) {
            $query->where('user_id', Auth::id());
        })->withCount(['users as users_count' => function($query) {
            $query->where('group_user.status', 'active');
        }])->get();
// Demandes en attente
$pendingRequests = Auth::user()->groups()
    ->wherePivot('status', 'pending')
    ->get();

// Demandes refusées non notifiées
$rejectedRequests = Auth::user()->groups()
    ->wherePivot('status', 'rejected')
    ->wherePivot('is_notified', false)
    ->get();

return view('livewire.groups.list-groups', [
    'userGroups' => $userGroups,
    'availableGroups' => $availableGroups,
    'pendingRequests' => $pendingRequests,
    'rejectedRequests' => $rejectedRequests,
])->layout('layouts.app');
    }

    public function dismissNotification($groupId)
    {
        Auth::user()->groups()->updateExistingPivot($groupId, ['is_notified' => true]);
    }
}
