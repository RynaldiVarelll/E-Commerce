@extends('layouts.frontend')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 relative overflow-hidden">
    
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute top-[10%] left-[5%] w-64 h-64 bg-green-200 rounded-full blur-[100px] opacity-40 animate-pulse"></div>
        <div class="absolute bottom-[10%] right-[5%] w-64 h-64 bg-blue-200 rounded-full blur-[100px] opacity-40 animate-pulse"></div>
    </div>

    <div class="max-w-xl w-full relative z-10">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 overflow-hidden transform transition-all duration-700 hover:scale-[1.01]">
            
            <div class="bg-gradient-to-b from-green-50 to-white pt-12 pb-8 text-center">
                <div class="relative inline-block">
                    <div class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-20"></div>
                    <div class="relative w-24 h-24 bg-green-500 rounded-full flex items-center justify-center shadow-lg shadow-green-200 animate-bounce-short">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-3xl font-black text-gray-900 mt-6 tracking-tight animate-fade-in-up">Payment Successful!</h1>
                <p class="text-gray-500 font-medium animate-fade-in-up delay-100">Hore! Pesananmu sedang kami siapkan.</p>
            </div>

            <div class="px-8 pb-10">
                <div class="bg-gray-50 rounded-[2rem] p-6 border-2 border-dashed border-gray-200 relative">
                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white rounded-full"></div>
                    <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white rounded-full"></div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Invoice Code</span>
                            <span class="font-mono font-bold text-gray-800 bg-white px-3 py-1 rounded-lg border border-gray-100">
                                #{{ $transaction->invoice_code }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Total Pembayaran</span>
                            <span class="text-xl font-black text-blue-600">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 border-dashed">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Status</span>
                            <span class="flex items-center text-xs font-black px-3 py-1 bg-blue-100 text-blue-600 rounded-full">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                                {{ strtoupper($transaction->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- FIX: Memanggil rute tanpa prefix admin. --}}
                    <a href="{{ route('transactions.print-invoice', $transaction->id) }}" 
                       class="flex items-center justify-center px-6 py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-black transition-all transform active:scale-95">
                       <i class="fa-solid fa-file-invoice mr-2"></i> Download Struk
                    </a>

                    <a href="{{ route('product.index') }}" 
                       class="flex items-center justify-center px-6 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all transform active:scale-95">
                       <i class="fa-solid fa-cart-shopping mr-2"></i> Belanja Lagi
                    </a>
                </div>
                
                <p class="text-center mt-8 text-xs text-gray-400 font-medium">
                    Butuh bantuan? <a href="#" class="text-blue-500 hover:underline">Hubungi Support</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var duration = 3 * 1000;
        var end = Date.now() + duration;

        (function frame() {
            confetti({
                particleCount: 3,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
                colors: ['#22c55e', '#3b82f6']
            });
            confetti({
                particleCount: 3,
                angle: 120,
                spread: 55,
                origin: { x: 1 },
                colors: ['#22c55e', '#3b82f6']
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());
    });
</script>

<style>
    @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-short { animation: bounce-short 2s ease-in-out infinite; }
    .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .delay-100 { animation-delay: 0.1s; }
</style>
@endsection