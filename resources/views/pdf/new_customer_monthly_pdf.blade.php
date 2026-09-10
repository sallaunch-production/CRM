<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Monthly New Customers</title>

    <style>
        @page {
            margin: 2cm 1.5cm 2cm 1.5cm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }

        h3 {
            font-size: 15px;
            margin: 0 0 5px 0;
            text-align: center;
        }

        p.subtitle {
            font-size: 10px;
            margin: 0 0 12px 0;
            text-align: center;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        thead th {
            background-color: #E9ECEF;
            color: #212529;
            font-weight: bold;
            text-align: center;
            padding: 5px 3px;
            border: 1px solid #B7B7B7;
        }

        tbody td {
            padding: 4px 3px;
            border: 1px solid #B7B7B7;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background-color: #F2F2F2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h3>Monthly New Customers</h3>

    {{-- Periode laporan bulanan --}}

<p class="subtitle">
    Periode:
    {{ \Carbon\Carbon::parse($tanggalDari)->locale('id')->translatedFormat('F Y') }}
</p>

    <table>

        <thead>
            <tr>
                <th style="width: 4%;">No.</th>
                <th style="width: 13%;">Nomor Referensi</th>
                <th style="width: 15%;">Nama Pengirim</th>
                <th style="width: 13%;">Nomor Identitas</th>
                <th style="width: 12%;">Nomor Telepon</th>
                <th style="width: 12%;">Negara Tujuan</th>
                <th style="width: 12%;">Nominal Transaksi</th>
                <th style="width: 11%;">Tanggal Transaksi</th>
                <th style="width: 8%;">Nopend</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($data as $row)
                <tr>

                    {{-- No --}}
                    <td class="text-center">
                        {{ $loop->iteration }}.
                    </td>

                    {{-- Nomor Referensi --}}
                    <td class="text-center">
                        {{ $row->no_referensi }}
                    </td>

                    {{-- Nama Pengirim --}}
                    <td>
                        {{ $row->nama_pengirim }}
                    </td>

                    {{-- Nomor Identitas --}}
                    <td class="text-center">
                        {{ $row->nomor_identitas }}
                    </td>

                    {{-- Nomor Telepon --}}
                    <td class="text-center">
                        {{ $row->nomor_telepon }}
                    </td>

                    {{-- Negara Tujuan --}}
                    <td>
                        {{ $row->negara_tujuan }}
                    </td>

                    {{-- Nominal Transaksi --}}
                    <td class="text-center">
                        {{ $row->jumlah_transaksi }}
                    </td>

                    {{-- Tanggal Transaksi --}}
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($row->tanggal_transaksi)->format('d-m-Y H:i:s') }}
                    </td>

                    {{-- Nopend --}}
                    <td class="text-center">
                        {{ $row->nopend }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="9" class="text-center">
                        No data found.
                    </td>
                </tr>
            @endforelse

        </tbody>

    </table>

</body>

</html>
