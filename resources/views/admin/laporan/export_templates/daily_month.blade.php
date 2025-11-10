<table>
    <thead>
        {{-- Header Neraca --}}
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Tanggal</th>
            <th colspan="4">Timbulan Sampah (Kg)</th>
            <th colspan="4">Terkelola (Internal) (Kg)</th>
            <th colspan="4">Tidak Terkelola (Diserahkan) (Kg)</th>
        </tr>
        <tr>
            <th>Organik</th> <th>Anorganik</th> <th>Residu</th> <th>Total</th>
            <th>Organik</th> <th>Anorganik</th> <th>Residu</th> <th>Total</th>
            <th>Organik</th> <th>Anorganik</th> <th>Residu</th> <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dailyData as $index => $row)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $row['tanggal'] }}</td>
            <td>{{ $row['timbulan_organik'] }}</td>
            <td>{{ $row['timbulan_anorganik'] }}</td>
            <td>{{ $row['timbulan_residu'] }}</td>
            <td>{{ $row['total_timbulan'] }}</td>
            <td>{{ $row['terkelola_organik'] }}</td>
            <td>{{ $row['terkelola_anorganik'] }}</td>
            <td>{{ $row['terkelola_residu'] }}</td>
            <td>{{ $row['total_terkelola'] }}</td>
            <td>{{ $row['diserahkan_organik'] }}</td>
            <td>{{ $row['diserahkan_anorganik'] }}</td>
            <td>{{ $row['diserahkan_residu'] }}</td>
            <td>{{ $row['total_diserahkan'] }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total:</th>
            <th>{{ $totalsNeraca['timbulan_organik'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['timbulan_anorganik'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['timbulan_residu'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['total_timbulan'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['terkelola_organik'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['terkelola_anorganik'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['terkelola_residu'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['total_terkelola'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['diserahkan_organik'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['diserahkan_anorganik'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['diserahkan_residu'] ?? 0 }}</th>
            <th>{{ $totalsNeraca['total_diserahkan'] ?? 0 }}</th>
        </tr>
    </tfoot>
    
    {{-- Spacer --}}
    <tr><td colspan="14"></td></tr>
    
    {{-- Header Area --}}
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            @foreach($lokasis as $nama)
            <th>{{ $nama }}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dailyData as $index => $row)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $row['tanggal'] }}</td>
            @foreach($lokasis as $id => $nama)
            <td>{{ $row["area_lokasi_$id"] ?? 0 }}</td>
            @endforeach
            <td>{{ $row['area_total_harian'] }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
         <tr>
            <th colspan="2">Total:</th>
            @foreach($lokasis as $id => $nama)
            <th>{{ $totalsArea["lokasi_$id"] ?? 0 }}</th>
            @endforeach
            <th>{{ $totalsArea['total'] ?? 0 }}</th>
        </tr>
    </tfoot>
</table>