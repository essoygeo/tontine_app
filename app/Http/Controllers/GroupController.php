<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;


class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        return view('groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'cotisation_fixe' => $request->cotisation_fixe,
            'creator_id' => auth()->id(),
        ]);

        // auto ajouter le créateur comme admin
        $group->users()->attach(auth()->id(), ['role' => 'admin']);

        return redirect()->back();
    }

}
