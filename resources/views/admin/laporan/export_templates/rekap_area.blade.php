<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Bulan</th>
            @foreach($lokasis as $nama)
            <th>{{ $nama }}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapData as $index => $row)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $row['bulan'] }}</td>
            @foreach($lokasis as $id => $nama)
            <td>{{ $row["lokasi_$id"] ?? 0 }}</td>
            @endforeach
            <td>{{ $row['total_all_area'] }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total:</th>
            @foreach($lokasis as $id => $nama)
            <th>{{ $totals["lokasi_$id"] ?? 0 }}</th>
            @endforeach
            <th>{{ $totals['total_all_area'] ?? 0 }}</th>
        </tr>
    </tfoot>
</table>