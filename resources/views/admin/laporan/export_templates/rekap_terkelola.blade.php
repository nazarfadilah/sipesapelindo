<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Bulan</th>
            <th>Timbulan (Kg)</th>
            <th>Terkelola (Kg)</th>
            <th>Tidak Terkelola (Kg)</th>
            <th>% Terkelola</th>
            <th>% Tidak Terkelola</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapData as $index => $row)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $row['bulan'] }}</td>
            <td>{{ $row['total_timbulan'] }}</td>
            <td>{{ $row['total_terkelola'] }}</td>
            <td>{{ $row['total_diserahkan'] }}</td>
            <td>{{ number_format($row['persen_terkelola'], 2) }}%</td>
            <td>{{ number_format($row['persen_diserahkan'], 2) }}%</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total:</th>
            <th>{{ $totals['total_timbulan'] ?? 0 }}</th>
            <th>{{ $totals['total_terkelola'] ?? 0 }}</th>
            <th>{{ $totals['total_diserahkan'] ?? 0 }}</th>
            <th>{{ number_format($totals['persen_terkelola'] ?? 0, 2) }}%</th>
            <th>{{ number_format($totals['persen_diserahkan'] ?? 0, 2) }}%</th>
        </tr>
    </tfoot>
</table>