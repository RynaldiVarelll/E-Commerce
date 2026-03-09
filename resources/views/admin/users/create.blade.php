@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Tambah Admin / User</h2>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700">Nama</label>
            <input type="text" name="name" class="w-full border px-4 py-2 rounded" value="{{ old('name') }}" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" class="w-full border px-4 py-2 rounded" value="{{ old('email') }}" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Role</label>
            <select name="role" class="w-full border px-4 py-2 rounded" required>
                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Seller)</option>
            </select>
            @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password" class="w-full border px-4 py-2 rounded" required>
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full border px-4 py-2 rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-bold">
            Simpan
        </button>
    </form>
</div>
@endsection
