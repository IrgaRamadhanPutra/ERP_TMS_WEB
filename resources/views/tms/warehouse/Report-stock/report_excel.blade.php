<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Stock</title>
</head>

<body>
    <table>
        <tr>
            <td colspan="6" style="font-size:15px;"><b>Report Stock</b></td>
        </tr>
        <tr>
            <td colspan="6">Date Export : {{ $date }}</td>
        </tr>
        <tr>
            <td colspan="6" style="padding-bottom: 10px;"></td> <!-- Spasi bawah dengan CSS -->
        </tr>
        <tr>
            <td colspan="6">Sloc : {{ $slocNo }}</td>
        </tr>
        <tr>
            <td colspan="6" style="padding-bottom: 10px;"></td> <!-- Spasi bawah dengan CSS -->
        </tr>
        <tr>
            <td colspan="6">Plant : {{ $plant }}</td>
        </tr>
    </table>
    <br>

    <table>
        <tr>
            <th style="font-size: 11px;">&nbsp;NO</th>
            <th style="font-size: 11px;text-align:center">&nbsp;MATERIAL NO</th>
            <th style="font-size: 11px;text-align:center">&nbsp;PART NO</th>
            <th style="font-size: 11px;text-align:center">&nbsp;MATERIAL DESC</th>
            <th style="font-size: 11px;text-align:center">&nbsp;PLANT</th>
            <th style="font-size: 11px;text-align:center">&nbsp;SLOC</th>
            <th style="font-size: 11px;text-align:center">&nbsp;QTY</th>
            <th style="font-size: 11px;text-align:center">&nbsp;SATUAN</th>
            <th style="font-size: 11px;text-align:center">&nbsp;SATUAN CONV</th>
            <th style="font-size: 11px;text-align:center">&nbsp;QTY CONV</th>
            <th style="font-size: 11px;text-align:center">&nbsp;BACTH</th>
        </tr>
        @foreach ($data as $a => $q)
            <tr>
                <td style="font-size: 11px;text-align:center;">{{ $loop->iteration }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['material_no'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['old_matno'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['material_desc'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['plant'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['sloc'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['quantity'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['satuan'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['satuan_conv'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['quantity_conv'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['batch'] }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
