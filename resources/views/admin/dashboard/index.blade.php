@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-500 text-sm">Total Users</h2>
            <p class="text-2xl font-bold">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-500 text-sm">Total Transactions</h2>
            <p class="text-2xl font-bold">{{ $totalTransactions }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-500 text-sm">Total Revenue</h2>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-500 text-sm">Total Products</h2>
            <p class="text-2xl font-bold">{{ $totalProducts }}</p>
        </div>
    </div>

</div>
@if (session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        {{ session('error') }}
    </div>
@endif
<div class="flex items-center justify-between mb-3">
    <h2 class="text-xl font-semibold text-gray-800">Recent Transactions</h2>
    
    <a href="{{ route('transactions.report') }}" 
       class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
        <i class="fa-solid fa-print mr-2"></i>
        Generate Report
    </a>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full table-auto">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="px-4 py-2 text-left"><i class="fa-solid fa-user"></i> User</th>
                <th class="px-4 py-2 text-left"><i class="fa-solid fa-file-invoice-dollar"></i> Total</th>
                <th class="px-4 py-2 text-left"> <i class="fa-solid fa-circle-info"></i> Status</th>
                <th class="px-4 py-2 text-left"><i class="fa-regular fa-calendar"></i> Tanggal</th>
                <th class="px-4 py-2 text-left"><i class="fa-solid fa-circle-check"></i>  Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recentTransactions as $tx)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $tx->user->name ?? 'Unknown' }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">
    @php
        $statusClasses = [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'confirmed' => 'bg-blue-100 text-blue-700',
            'shipped' => 'bg-indigo-100 text-indigo-700',
            'completed' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
        ];

        $statusIcons = [
            'pending' => 'fa-clock',
            'confirmed' => 'fa-circle-check',
            'shipped' => 'fa-truck',
            'completed' => 'fa-check-circle',
            'cancelled' => 'fa-times-circle',
        ];

        $colorClass = $statusClasses[$tx->status] ?? 'bg-gray-100 text-gray-700';
        $icon = $statusIcons[$tx->status] ?? 'fa-question-circle';
    @endphp

    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
        <i class="fas {{ $icon }} mr-2"></i>
        {{ ucfirst($tx->status) }}
    </span>
</td>

                    <td class="px-4 py-2">{{ $tx->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-2 flex items-center space-x-2">
    <form method="POST" id="form-{{ $tx->id }}">
        @csrf
        <select onchange="updateStatus({{ $tx->id }}, this.value)" class="border rounded p-1">
            <option disabled selected>Status</option>
            @foreach(['pending', 'confirmed', 'shipped', 'completed', 'cancelled'] as $status)
                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </form>

    <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
function updateStatus(transactionId, status) {
    if (!status) return;
    if (confirm(`Ubah status transaksi menjadi "${status}"?`)) {
        const form = document.getElementById(`form-${transactionId}`);
        form.action = `/transactions/${transactionId}/${status}`;
        form.submit();
    }
}
</script>

@endsection
