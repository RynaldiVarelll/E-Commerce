@extends('layouts.frontend')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12 text-center">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
        </svg>
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
    <i class="fa-solid fa-circle-check mr-2"></i> 
    Transaksi berhasil! Invoice: <strong>{{ $transaction->invoice_code }}</strong>
</div>

        <h1 class="text-3xl font-bold text-gray-800 mb-3">Payment Successful!</h1>
        <p class="text-gray-600 mb-6">Thank you for your purchase.</p>

        <div class="bg-gray-50 p-4 rounded-lg text-left inline-block mx-auto">
            <p><span class="font-semibold">Invoice Code:</span> {{ $transaction->invoice_code }}</p>
            <p><span class="font-semibold">Total Amount:</span> Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
            <p><span class="font-semibold">Status:</span> <span class="text-blue-600">{{ ucfirst($transaction->status) }}</span></p>
        </div>
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
            <div class="mt-8">
    <a href="{{ route('transactions.print-invoice', $transaction->id) }}" 
       class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
       <i class="fa-solid fa-file-invoice"></i> Download Invoice
    </a></div>
        <div class="mt-8">
            <a href="{{ route('product.index') }}" 
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
              <i class="fa-solid fa-cart-shopping"></i> Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
