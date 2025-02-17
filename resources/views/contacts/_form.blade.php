<div>
    <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
    <input type="text" id="name" name="name" value="{{ old('name', $contact->name ?? '') }}" required
           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('name') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
</div>

<div>
    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
    <input type="email" id="email" name="email" value="{{ old('email', $contact->email ?? '') }}" required
           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('email') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
</div>

<div>
    <label for="phone" class="block text-gray-700 font-bold mb-2">Phone:</label>
    <input type="tel" id="phone" name="phone" value="{{ old('phone', $contact->phone ?? '') }}" required
           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('phone') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
</div>

<div class="flex justify-end space-x-2">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{ isset($contact) ? 'Update' : 'Save' }}  </button>
    <a href="{{ route('contacts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Back
    </a>
</div>
