@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-10">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">Admin Dashboard</h1>
            <p class="text-gray-500 mt-1 font-medium">Selamat datang kembali! Berikut ringkasan performa toko hari ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.transactions.report') }}" 
               class="inline-flex items-center bg-gray-900 text-white px-6 py-3 rounded-2xl font-bold shadow-xl shadow-gray-200 hover:bg-black transition-all transform active:scale-95">
                <i class="fa-solid fa-file-export mr-2 text-green-400"></i>
                Generate Report
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        @php
            $stats = [
                ['label' => 'Total Users', 'value' => $totalUsers, 'icon' => 'fa-users', 'color' => 'blue'],
                ['label' => 'Total Transactions', 'value' => $totalTransactions, 'icon' => 'fa-receipt', 'color' => 'indigo'],
                ['label' => 'Total Revenue', 'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'icon' => 'fa-wallet', 'color' => 'green'],
                ['label' => 'Total Products', 'value' => $totalProducts, 'icon' => 'fa-box-open', 'color' => 'orange'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-{{ $stat['color'] }}-100 text-{{ $stat['color'] }}-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid {{ $stat['icon'] }} text-xl"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Live Data</span>
            </div>
            <h2 class="text-gray-500 text-sm font-bold uppercase tracking-tight">{{ $stat['label'] }}</h2>
            <p class="text-3xl font-black text-gray-900 mt-1 leading-none">{{ $stat['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl mb-6 flex items-center animate-fade-in">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Recent Transactions Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Recent Transactions</h2>
            <div class="flex gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 text-[11px] font-black uppercase tracking-[0.2em]">
                        <th class="px-8 py-5 text-left">User</th>
                        <th class="px-8 py-5 text-left">Total Amount</th>
                        <th class="px-8 py-5 text-left">Status</th>
                        <th class="px-8 py-5 text-left">Tanggal</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($recentTransactions as $tx)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black text-xs mr-3">
                                        {{ strtoupper(substr($tx->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $tx->user->name ?? 'Unknown User' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 font-bold text-gray-700">
                                Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'confirmed' => 'bg-blue-100 text-blue-700',
                                        'shipped' => 'bg-indigo-100 text-indigo-700',
                                        'completed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                    ];
                                    $colorClass = $statusClasses[$tx->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-widest {{ $colorClass }}">
                                    {{ $tx->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-sm text-gray-500 font-medium">
                                {{ $tx->created_at->format('d M, Y') }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <form method="POST" id="form-{{ $tx->id }}">
                                        @csrf
                                        <select onchange="updateStatus({{ $tx->id }}, this.value)" 
                                                class="bg-gray-50 border border-gray-200 text-xs font-bold rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                                            <option disabled selected>Update</option>
                                            @foreach(['pending', 'confirmed', 'shipped', 'completed', 'cancelled'] as $status)
                                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                            @endforeach
                                        </select>
                                    </form>

                                    <form action="{{ route('admin.transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function updateStatus(transactionId, status) {
    if (!status) return;
    
    Swal.fire({
        title: 'Ubah Status?',
        text: `Apakah Anda yakin ingin mengubah status menjadi ${status}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Update!',
        customClass: {
            popup: 'rounded-[2rem]'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-${transactionId}`);
            // 🔥 FIX: Menambahkan /admin di depan URL agar sesuai dengan prefix rute admin
            form.action = `/admin/transactions/${transactionId}/${status}`;
            form.submit();
        }
    });
}
</script>
@endsection