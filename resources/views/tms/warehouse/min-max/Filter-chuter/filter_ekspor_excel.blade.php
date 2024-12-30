<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Chuter</title>
</head>

<body>
    <table>
        <tr>
            <td colspan="6" style="font-size:15px;"><b>Transaksi Chuter</b></td>
        </tr>
        <tr>
            <td colspan="6">Date Export : {{ $date }}</td>
        </tr>
        <tr>
            <td colspan="6" style="padding-bottom: 10px;"></td> <!-- Spasi bawah dengan CSS -->
        </tr>
        <tr>
            <td colspan="6">From Date : {{ $fromDate }}</td>
        </tr>
        <tr>
            <td colspan="6">To Date : {{ $toDate }}</td>
        </tr>
    </table>

    <br>

    <table>
        <tr>
            <th style="font-size: 11px;">&nbsp;NO</th>
            <th style="font-size: 11px;">&nbsp;CHUTTER ADDRESS</th>
            <th style="font-size: 11px;text-align:center">&nbsp;KANBAN NO </th>
            <th style="font-size: 11px;text-align:center">&nbsp;PART NO</th>
            <th style="font-size: 11px;text-align:center">&nbsp;SEQUENCE </th>
            <th style="font-size: 11px;text-align:center">&nbsp;QTY</th>
            <th style="font-size: 11px;text-align:center">&nbsp;KANBAN PRINT</th>
            <th style="font-size: 11px;text-align:center">&nbsp;IN DATE</th>
            <th style="font-size: 11px;text-align:center">&nbsp;OUT DATE</th>
        </tr>

        @foreach ($data as $a => $q)
            <tr>
                <td style="font-size: 11px;text-align:center;">{{ $loop->iteration }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['chutter_address'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['kanban_no'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['part_no'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['seq'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['qty'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['creation_date'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['in_datetime'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['out_datetime'] }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
