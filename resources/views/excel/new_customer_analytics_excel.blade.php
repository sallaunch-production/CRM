<table>
    <thead>
        <tr>
            <th colspan="9">
                New Customer {{ $type === 'send' ? 'Send' : 'Receive' }}
                ({{ $startDate }} s/d {{ $endDate }})
            </th>
        </tr>
        <tr>
            <th>No.</th>
            <th>Nomor Referensi</th>
            <th>{{ $type === 'send' ? 'Nama Pengirim' : 'Nama Penerima' }}</th>
            <th>Nomor Identitas</th>
            <th>Nomor Telepon</th>
            <th>{{ $type === 'send' ? 'Negara Tujuan' : 'Negara Pengirim' }}</th>
            <th>Nominal Transaksi</th>
            <th>Tanggal Transaksi</th>
            <th>Nopend</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->no_referensi }}</td>
                <td>{{ $type === 'send' ? $row->nama_pengirim : $row->nama_penerima }}</td>
                <td>&#8203;{{ $row->nomor_identitas }}</td>
                <td>&#8203;{{ $row->nomor_telepon }}</td>
                <td>{{ $type === 'send' ? $row->negara_tujuan : $row->negara_pengirim }}</td>
                <td>{{ (float) $row->jumlah_transaksi }}</td>
                <td>{{ $row->tanggal_transaksi }}</td>
                <td>&#8203;{{ $row->nopend }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>
