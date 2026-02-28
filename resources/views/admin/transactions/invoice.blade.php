<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $transaction->invoice_code }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 2px solid #444; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #2563eb; text-transform: uppercase; }
        
        .info-table { width: 100%; border: none; margin-bottom: 30px; }
        .info-table td { border: none; padding: 0; vertical-align: top; }
        
        table.items { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.items th { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; text-align: left; text-transform: uppercase; font-size: 12px; }
        table.items td { border: 1px solid #e2e8f0; padding: 12px; vertical-align: middle; }
        
        .product-img { width: 60px; height: 60px; object-cover: cover; border-radius: 8px; }
        
        .total-section { margin-top: 30px; text-align: right; }
        .total-amount { font-size: 20px; font-weight: bold; color: #2563eb; }
        
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invoice</h1>
            <p><strong>ID Transaksi:</strong> #{{ $transaction->id }}</p>
        </div>

        <table class="info-table">
            <tr>
                <td>
                    <strong>Kepada:</strong><br>
                    {{ $transaction->user->name }}<br>
                    {{ $transaction->user->email }}
                </td>
                <td style="text-align: right;">
                    <strong>Tanggal:</strong> {{ $transaction->created_at->format('d F Y, H:i') }} WIB<br>
                    <strong>Metode Kirim:</strong> {{ $transaction->shippingMethod->name ?? 'N/A' }} ({{ $transaction->shippingMethod->service ?? '-' }})<br>
                    <strong>Status:</strong> <span style="color: #059669;">{{ strtoupper($transaction->status) }}</span>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th width="80">Produk</th>
                    <th>Detail</th>
                    <th width="100">Harga</th>
                    <th width="50">Qty</th>
                    <th width="120">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr>
                    <td>
                        {{-- Menggunakan image_url yang sudah terbukti muncul --}}
                        <img src="{{ $item->product->image_url }}" class="product-img" alt="img">
                    </td>
                    <td>
                        <strong>{{ $item->product->name ?? 'Produk Terhapus' }}</strong>
                    </td>
                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <p>Total Pembayaran:</p>
            <p class="total-amount">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
        </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja di Invoify Indonesia.</p>
            <p>Simpan invoice ini sebagai bukti pembelian yang sah.</p>
        </div>
    </div>
</body>
</html>