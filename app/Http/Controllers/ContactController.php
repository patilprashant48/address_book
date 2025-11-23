<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::all();

        // Load contacts + their groups
        $contactsQuery = Contact::with('groups');

        // Filter contacts using pivot table
        if ($request->filled('group')) {
            $contactsQuery->whereHas('groups', function ($q) use ($request) {
                $q->where('group_id', $request->group);
            });
        }

        $contacts = $contactsQuery->get();

        return view('contacts.index', compact('contacts', 'groups'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email',
            'phone'  => 'required',
            'groups' => 'array'
        ]);

        // Save contact
        $contact = Contact::create($request->only('name', 'email', 'phone'));

        // Save pivot table links
        if ($request->groups) {
            $contact->groups()->sync($request->groups);
        }

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Contact added successfully!');
    }
}
