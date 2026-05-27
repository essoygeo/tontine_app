<?php

namespace App\Livewire\Contributions;

use Livewire\Component;
use App\Models\Contribution;
use Illuminate\Support\Facades\Auth;

class ListContributions extends Component
{
    public function render()
    {
        $contributions = Auth::user()->contributions()
            ->where('status', 'paid')
            ->with('group')
            ->latest()
            ->get();

        return view('livewire.contributions.list-contributions', [
            'contributions' => $contributions,
        ])->layout('layouts.app');
    }
}
