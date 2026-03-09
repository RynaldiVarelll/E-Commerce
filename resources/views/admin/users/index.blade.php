@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Daftar Admin & User</h2>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Admin/User</a>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-2 px-4 text-left">Nama</th>
                <th class="py-2 px-4 text-left">Email</th>
                <th class="py-2 px-4 text-left">Role</th>
                <th class="py-2 px-4 text-right">Aksi</th>
            </tr> 
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-b">
                <td class="py-2 px-4">{{ $user->name }}</td>
                <td class="py-2 px-4 text-gray-500">{{ $user->email }}</td>
                <td class="py-2 px-4">
                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="py-2 px-4 text-right space-x-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Hapus admin/user ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
