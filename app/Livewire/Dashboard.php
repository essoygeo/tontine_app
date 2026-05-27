<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $notifications = [];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        $this->notifications = [];

        // Demandes refusées non notifiées
        $rejected = $user->groups()
            ->wherePivot('status', 'rejected')
            ->wherePivot('is_notified', false)
            ->get();

        foreach ($rejected as $group) {
            $this->notifications[] = [
                'id' => $group->id,
                'type' => 'rejected',
                'message' => "Votre demande pour rejoindre le cercle '{$group->name}' a été refusée.",
            ];
        }

        // Demandes acceptées non notifiées
        $accepted = $user->groups()
            ->wherePivot('status', 'active')
            ->wherePivot('is_notified', false)
            ->get();

        foreach ($accepted as $group) {
            $this->notifications[] = [
                'id' => $group->id,
                'type' => 'accepted',
                'message' => "Félicitations ! Vous avez été accepté dans le cercle '{$group->name}'.",
            ];
        }
    }

    public function dismissNotification($groupId)
    {
        $user = Auth::user();
        $user->groups()->updateExistingPivot($groupId, ['is_notified' => true]);
        $this->loadNotifications();
    }

    public function render()
    {
        $user = Auth::user();
        $groups = $user->groups()
            ->wherePivot('status', 'active')
            ->withCount(['users' => function($query) {
                $query->where('group_user.status', 'active');
            }])->get();
        $totalContributed = $user->contributions()->where('status', 'paid')->sum('amount');
        $incompleteAmount = $user->contributions()->where('status', 'pending')->sum('amount');
        $recentContributions = $user->contributions()->where('status', 'paid')->with('group')->latest()->take(5)->get();

        return view('livewire.dashboard', [
            'groups' => $groups,
            'totalContributed' => $totalContributed,
            'incompleteAmount' => $incompleteAmount,
            'recentContributions' => $recentContributions,
        ])->layout('layouts.app');
    }
}
