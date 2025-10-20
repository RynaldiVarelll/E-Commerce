<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $transaction->invoice_code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; }
        .container { width: 90%; margin: 0 auto; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f5f5f5; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invoice Pembelian</h1>
        <p><strong>Kode Invoice:</strong> {{ $transaction->invoice_code }}</p>
        <p><strong>Nama Pembeli:</strong> {{ $transaction->user->name }}</p>
        <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Kuantitas</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>Rp{{ number_format($item->product->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Total: Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
        <p>Terima kasih telah berbelanja di toko kami.</p>
    </div>
</body>
</html>
