<table>
    <thead>
        <tr>
            <th>Jenis Akun</th>
            @foreach ($report_ekuitas as $ekuitas)
                <th>{{ $ekuitas['jenis_account'] }}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Saldo Awal</strong></td>
            @foreach ($report_ekuitas as $ekuitas)
                <td>{{ $ekuitas['saldo_awal'] }}</td>
            @endforeach
            <td>{{ collect($report_ekuitas)->sum('saldo_awal') }}</td>
        </tr>
        <tr>
            <td><strong>Penambahan (Pengurangan)</strong></td>
            @foreach ($report_ekuitas as $ekuitas)
                <td>{{ $ekuitas['penambahan'] }}</td>
            @endforeach
            <td>{{ collect($report_ekuitas)->sum('penambahan') }}</td>
        </tr>
        <tr>
            <td><strong>Saldo Akhir</strong></td>
            @foreach ($report_ekuitas as $ekuitas)
                <td>{{ $ekuitas['saldo_akhir'] }}</td>
            @endforeach
            <td>{{ collect($report_ekuitas)->sum('saldo_akhir') }}</td>
        </tr>
    </tbody>
</table>
