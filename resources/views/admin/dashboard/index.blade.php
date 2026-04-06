@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-10">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Admin Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 font-medium">Selamat datang kembali! Berikut ringkasan performa toko hari ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.transactions.report') }}" 
               class="inline-flex items-center bg-gray-900 dark:bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold shadow-xl shadow-gray-200 dark:shadow-none hover:bg-black dark:hover:bg-blue-700 transition-all transform active:scale-95">
                <i class="fa-solid fa-file-export mr-2 text-green-400 dark:text-white"></i>
                Generate Report
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        @php
            $stats = [
                ['label' => 'Total Users', 'value' => $totalUsers, 'icon' => 'fa-users', 'bg' => 'bg-blue-100 dark:bg-blue-900/20', 'icon_bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-600 dark:text-blue-400'],
                ['label' => 'Total Transactions', 'value' => $totalTransactions, 'icon' => 'fa-receipt', 'bg' => 'bg-indigo-100 dark:bg-indigo-900/20', 'icon_bg' => 'bg-indigo-100 dark:bg-indigo-900/30', 'text' => 'text-indigo-600 dark:text-indigo-400'],
                ['label' => 'Total Revenue', 'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'icon' => 'fa-wallet', 'bg' => 'bg-green-100 dark:bg-green-900/20', 'icon_bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-600 dark:text-green-400'],
                ['label' => 'Total Products', 'value' => $totalProducts, 'icon' => 'fa-box-open', 'bg' => 'bg-orange-100 dark:bg-orange-900/20', 'icon_bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-600 dark:text-orange-400'],
                ['label' => 'Unread Chats', 'value' => $unreadChatCount, 'icon' => 'fa-comments', 'bg' => 'bg-blue-100 dark:bg-blue-900/20', 'icon_bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-600 dark:text-blue-400', 'link' => route('chat.index')],
            ];
        @endphp

        @foreach($stats as $stat)
        @isset($stat['link'])
        <a href="{{ $stat['link'] }}" class="block group">
        @endisset
        <div class="glass-panel p-6 rounded-[2.5rem] hover:shadow-2xl hover:shadow-gray-300/30 dark:hover:shadow-black/30 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden h-full">
            <div class="absolute -right-4 -top-4 w-24 h-24 {{ $stat['bg'] }} rounded-full blur-2xl opacity-60 pointer-events-none"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 {{ $stat['icon_bg'] }} {{ $stat['text'] }} rounded-2xl flex items-center justify-center group-hover:rotate-12 transition-transform">
                    <i class="fa-solid {{ $stat['icon'] }} text-xl"></i>
                </div>
                @if(isset($stat['link']) && $stat['value'] > 0)
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                @else
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Live Data</span>
                @endif
            </div>
            <h2 class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-tight">{{ $stat['label'] }}</h2>
            <p class="text-3xl font-black text-gray-900 dark:text-white mt-1 leading-none">{{ $stat['value'] }}</p>
        </div>
        @isset($stat['link'])
        </a>
        @endisset
        @endforeach
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 gap-8 mb-12">
        {{-- Revenue Chart (Sales) --}}
        <div class="glass-panel p-6 rounded-[2.5rem] shadow-sm">
            <h2 class="text-xl font-black text-gray-900 dark:text-white mb-4">Grafik Penjualan (7 Hari Terakhir)</h2>
            <div class="relative h-72 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- User Registration Chart (Super Admin Only) --}}
        @if(auth()->user()->isSuperAdmin())
        <div class="glass-panel p-6 rounded-[2.5rem] shadow-sm">
            <h2 class="text-xl font-black text-gray-900 dark:text-white mb-4">Pendaftaran Pengguna (7 Hari Terakhir)</h2>
            <div class="relative h-72 w-full">
                <canvas id="userChart"></canvas>
            </div>
        </div>
        @endif
    </div>


    {{-- Recent Transactions Table --}}
    <div class="glass-panel rounded-[2.5rem] overflow-hidden">
        <div class="p-8 border-b border-gray-50 dark:border-gray-800 flex items-center justify-between">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Recent Transactions</h2>
            <div class="flex gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
            </div>
            {{-- Dark Mode Toggle --}}
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); window.dispatchEvent(new CustomEvent('dark-mode-toggled'))" 
                    class="p-2.5 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all group">
                <i class="fa-solid" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon'"></i>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-white/30 dark:bg-gray-800/30 text-gray-500 dark:text-gray-400 text-[11px] font-black uppercase tracking-[0.2em] backdrop-blur-md">
                        <th class="px-8 py-5 text-left">User</th>
                        @if(auth()->user()->isSuperAdmin())
                            <th class="px-8 py-5 text-left">Toko / Seller</th>
                        @endif
                        <th class="px-8 py-5 text-left">Total Amount</th>
                        <th class="px-8 py-5 text-left">Status</th>
                        <th class="px-8 py-5 text-left">Tanggal</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($recentTransactions as $tx)
                        <tr class="hover:bg-white/40 dark:hover:bg-gray-800/40 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black text-xs mr-3 overflow-hidden">
                                        @if(optional($tx->user)->profile_photo_path)
                                            <img src="{{ Storage::url($tx->user->profile_photo_path) }}" alt="{{ $tx->user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($tx->user->name ?? 'U', 0, 1)) }}
                                        @endif
                                    </div>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $tx->user->name ?? 'Unknown User' }}</span>
                                </div>
                            </td>
                            @if(auth()->user()->isSuperAdmin())
                                <td class="px-8 py-6 text-sm">
                                    <div class="flex items-center text-gray-500 font-bold mb-1">
                                        <i class="fa-solid fa-store mr-2 text-blue-500"></i> {{ $tx->seller->name ?? 'Official Store' }}
                                    </div>
                                </td>
                            @endif
                            <td class="px-8 py-6 font-bold text-gray-700 dark:text-gray-300">
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
                            <td class="px-8 py-6 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ $tx->created_at->format('d M, Y') }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    @if(!auth()->user()->isSuperAdmin())
                                        <form method="POST" id="form-{{ $tx->id }}">
                                            @csrf
                                            <select onchange="updateStatus({{ $tx->id }}, this.value)" 
                                                    class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-xs font-bold rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                                                <option disabled selected>Update</option>
                                                @foreach(['shipped', 'completed', 'cancelled'] as $status)
                                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Hapus Transaksi">
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

{{-- SweetAlert2 & Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Data
    const revenueLabels = {!! json_encode($chartLabels ?? []) !!};
    const rawDatasets = {!! json_encode($revenueDatasets ?? []) !!};
    const isDarkMode = document.documentElement.classList.contains('dark');
    
    // Array of nice colors for multiple sellers
    const colorPalette = [
        '#3b82f6', // blue
        '#8b5cf6', // violet
        '#ec4899', // pink
        '#f59e0b', // amber
        '#10b981', // emerald
        '#ef4444', // red
        '#06b6d4', // cyan
    ];

    if (document.getElementById('revenueChart')) {
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        
        // Build chart datasets dynamically
        const chartDatasets = rawDatasets.map((ds, index) => {
            let color = colorPalette[index % colorPalette.length];
            
            let gradient = ctxRev.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, hexToRgba(color, 0.5));
            gradient.addColorStop(1, hexToRgba(color, 0));

            return {
                label: ds.label,
                data: ds.data,
                borderColor: color,
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: isDarkMode ? '#111827' : '#ffffff',
                pointBorderColor: color,
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            };
        });

        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: chartDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: chartDatasets.length > 1, 
                        position: 'top',
                        labels: { 
                            usePointStyle: true, 
                            boxWidth: 8,
                            color: isDarkMode ? '#9ca3af' : '#6b7280'
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? 'rgba(17, 24, 39, 0.9)' : 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' }
                    },
                    y: {
                        grid: { color: isDarkMode ? 'rgba(255,255,255,0.05)' : '#f3f4f6', drawBorder: false, borderDash: [5, 5] },
                        ticks: {
                            color: isDarkMode ? '#9ca3af' : '#6b7280',
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'M';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + 'K';
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Helper function to convert hex to rgba
    function hexToRgba(hex, alpha) {
        let r = parseInt(hex.slice(1, 3), 16),
            g = parseInt(hex.slice(3, 5), 16),
            b = parseInt(hex.slice(5, 7), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    // User Data (Super Admin)
    @if(auth()->user()->isSuperAdmin() && isset($userData))
    const userLabels = {!! json_encode(array_keys($userData->toArray())) !!};
    const userValues = {!! json_encode(array_values($userData->toArray())) !!};

    if (document.getElementById('userChart')) {
        const ctxUser = document.getElementById('userChart').getContext('2d');
        
        let gradientUser = ctxUser.createLinearGradient(0, 0, 0, 400);
        gradientUser.addColorStop(0, 'rgba(16, 185, 129, 0.5)'); // emerald-500
        gradientUser.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(ctxUser, {
            type: 'bar',
            data: {
                labels: userLabels,
                datasets: [{
                    label: 'Pendaftar Baru',
                    data: userValues,
                    backgroundColor: '#10b981', // emerald-500
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDarkMode ? 'rgba(17, 24, 39, 0.9)' : 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' }
                    },
                    y: {
                        grid: { color: isDarkMode ? 'rgba(255,255,255,0.05)' : '#f3f4f6', drawBorder: false, borderDash: [5, 5] },
                        ticks: { stepSize: 1, color: isDarkMode ? '#9ca3af' : '#6b7280' }
                    }
                }
            }
        });
    }
    @endif
});

// Re-render charts when dark mode toggles manually
window.addEventListener('dark-mode-toggled', () => {
    location.reload(); // Simple way to redraw with new defaults
});

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
            container: darkMode ? 'dark-swal' : '',
            popup: 'rounded-[2rem] dark:bg-gray-800 dark:text-white dark:border-gray-700'
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