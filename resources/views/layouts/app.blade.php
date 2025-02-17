<!DOCTYPE html>
<html>
<head>
    <title>Contact Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">

    <header class="bg-blue-500 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Contact Manager</h1>
            <nav>
                <a href="{{ route('contacts.index') }}" class="mr-4 hover:text-gray-200">Contacts</a>
                <a href="{{ route('contacts.import.form') }}" class="hover:text-gray-200">Import</a>
            </nav>
        </div>
    </header>

    <div class="flex h-full">
        <aside class="w-64 bg-gray-200 p-4">
            <h2 class="text-xl font-bold mb-4">Actions</h2>
            <ul class="space-y-2">
                <li><a href="{{ route('contacts.create') }}" class="block p-2 rounded hover:bg-gray-300">Add Contact</a></li>
                <li>
                    <form action="{{ route('contacts.search') }}" method="GET">
                        <input type="text" name="search" placeholder="Search Contacts" class="w-full border border-gray-300 px-2 py-1 rounded mb-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Search</button>
                    </form>
                </li>
            </ul>
        </aside>

        <main class="flex-grow p-6 overflow-y-auto">
            <div class="container mx-auto">

                {{-- Flash Messages --}}
                @if (Session::has('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                        {{ Session::get('success') }}
                        {{ Session::forget('success') }}  </div>
                @endif

                @if (Session::has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        {{ Session::get('error') }}
                        {{ Session::forget('error') }}  </div>
                @endif

                @yield('content')

            </div>
        </main>
    </div>

    <div class="fixed bottom-4 right-4">
        <a href="{{ route('contacts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add Contact
        </a>
    </div>

</body>
</html>
