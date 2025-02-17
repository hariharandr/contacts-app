@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Contacts</h1>

    <div id="contact-list" class="bg-white shadow rounded p-4 mb-6 overflow-y-auto max-h-[800px]">
        @foreach ($contacts as $contact)
            <div class="border-b border-gray-200 py-2">
                <p class="font-medium">{{ $contact->name }}</p>
                <p class="text-gray-600">{{ $contact->email }}</p>
                <p class="text-gray-600">{{ $contact->phone }}</p>
                <a href="{{ route('contacts.edit', $contact) }}" class="text-blue-500 mr-2">Edit</a>
                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-500">Delete</button>
                </form>
            </div>
        @endforeach
    </div>

@endsection
