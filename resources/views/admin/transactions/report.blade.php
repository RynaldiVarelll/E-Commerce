<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f3f3f3; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Transaksi</h1>
    <p><strong>Periode:</strong> {{ $startDate }} s/d {{ $endDate }}</p>
    <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Nama Pembeli</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $i => $tx)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $tx->created_at->format('d/m/Y') }}</td>
                <td>{{ $tx->user->name }}</td>
                <td>{{ ucfirst($tx->status) }}</td>
                <td>Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 40px; text-align: right;">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
</body>
</html>
