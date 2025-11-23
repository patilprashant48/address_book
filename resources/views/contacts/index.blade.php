@extends('layouts.app')

@section('content')
<section id="contact-form">
    <h2>Add Contact</h2>

    <form action="{{ route('contacts.store') }}" method="POST">
        @csrf

        <div class="row">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="row">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="row">
            <label>Phone</label>
            <input type="text" name="phone" required>
        </div>

        <div class="row">
            <label>Groups (Hold Ctrl to select multiple)</label>
            <select name="groups[]" multiple>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit">Save</button>
    </form>

    @if(session('success'))
        <p class="alert alert-success">{{ session('success') }}</p>
    @endif
</section>

<hr>

<section id="filter" style="margin-top:20px;">
    <h3>Filter Contacts By Group</h3>

    <form method="GET" action="{{ route('contacts.index') }}" class="filter-form">
        <select name="group" onchange="this.form.submit()">
            <option value="">All Contacts</option>
            @foreach($groups as $group)
                <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>
                    {{ $group->name }}
                </option>
            @endforeach
        </select>
    </form>
</section>

<section id="contacts" style="margin-top:20px;">
    <h2>Contact List</h2>

    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Groups</th>
        </tr>
        </thead>

        <tbody>
        @foreach($contacts as $contact)
            <tr>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone }}</td>
                <td>
                    @foreach($contact->groups as $g)
                        <span class="badge">{{ $g->name }}</span>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>

@endsection
