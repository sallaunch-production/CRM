<table>
    <thead>
        <tr>
            <th colspan="9">
                New Customer {{ $type === 'send' ? 'Send' : 'Receive' }}
                ({{ $startDate }} to {{ $endDate }})
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Reference No</th>
            <th>{{ $type === 'send' ? 'Sender Name' : 'Recipient Name' }}</th>
            <th>ID Number</th>
            <th>Phone Number</th>
            <th>{{ $type === 'send' ? 'Destination Country' : 'Sender Country' }}</th>
            <th>Amount</th>
            <th>Transaction Date</th>
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
                <td colspan="9">No data.</td>
            </tr>
        @endforelse
    </tbody>
</table>
