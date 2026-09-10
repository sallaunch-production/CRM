<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Nomor Referensi</th>
            <th>Nama Pengirim</th>
            <th>Nomor Identitas</th>
            <th>Nomor Telepon</th>
            <th>Negara Tujuan</th>
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
                <td>{{ $row->nama_pengirim }}</td>
                <td>&#8203;{{ $row->nomor_identitas }}</td>
                <td>{{ $row->nomor_telepon }}</td>
                <td style="text-align:center;">{{ $row->negara_tujuan }}</td>
                <td>{{ $row->jumlah_transaksi }}</td>
                <td>{{ $row->tanggal_transaksi }}</td>
                <td>&#8203;{{ $row->nopend }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9">No data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
