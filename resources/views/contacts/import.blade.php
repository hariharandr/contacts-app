@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Import Contacts</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="xml_file" class="block text-gray-700 font-bold mb-2">XML File:</label>
                <input type="file" id="xml_file" name="xml_file" accept=".xml" required
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('xml_file') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Import
                </button>
                <a href="{{ route('contacts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                    Back
                </a>
            </div>
        </form>
    </div>
@endsection
