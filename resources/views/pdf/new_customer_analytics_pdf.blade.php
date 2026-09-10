<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Customer {{ $type === 'send' ? 'Send' : 'Receive' }}</title>
    <style>
        @page { margin: 24px; }
        body { color: #212529; font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h2 { margin: 0 0 5px; text-align: center; }
        .period { color: #555; margin: 0 0 16px; text-align: center; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #b7b7b7; padding: 5px; vertical-align: middle; }
        th { background: #e9ecef; text-align: center; }
        tbody tr:nth-child(even) { background: #f6f6f6; }
        .center { text-align: center; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>New Customer {{ $type === 'send' ? 'Send' : 'Receive' }}</h2>
    <p class="period">Periode {{ $startDate }} s/d {{ $endDate }}</p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Referensi</th>
                <th>{{ $type === 'send' ? 'Nama Pengirim' : 'Nama Penerima' }}</th>
                <th>Nomor Identitas</th>
                <th>Nomor Telepon</th>
                <th>{{ $type === 'send' ? 'Negara Tujuan' : 'Negara Pengirim' }}</th>
                <th>Nominal</th>
                <th>Tanggal</th>
                <th>Nopend</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $row->no_referensi ?: '-' }}</td>
                    <td>{{ $type === 'send' ? $row->nama_pengirim : $row->nama_penerima }}</td>
                    <td>{{ $row->nomor_identitas }}</td>
                    <td>{{ $row->nomor_telepon ?: '-' }}</td>
                    <td>{{ $type === 'send' ? $row->negara_tujuan : $row->negara_pengirim }}</td>
                    <td class="right">Rp {{ number_format((float) $row->jumlah_transaksi, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_transaksi)->format('d-m-Y H:i') }}</td>
                    <td class="center">{{ $row->nopend ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
