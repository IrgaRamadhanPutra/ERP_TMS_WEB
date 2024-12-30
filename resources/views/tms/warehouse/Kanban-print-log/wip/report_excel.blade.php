<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Kanban Print Log</title>
</head>

<body>
    <table>
        <tr>
            <td colspan="6" style="font-size:15px;"><b>Kanban Print Log  Wip</b></td>
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
        <tr>
            <td colspan="6">Kanban No : {{ $kanbanNowip }}</td>
        </tr>
        <tr>
            <td colspan="6">Created By : {{ $createdBywip }}</td>
        </tr>
    </table>
    <br>

    <table>
        <tr>
            <th style="font-size: 11px;">&nbsp;NO</th>
            <th style="font-size: 11px;text-align:center">&nbsp;KANBAN NO</th>
            <th style="font-size: 11px;text-align:center">&nbsp;ITEMCODE</th>
            <th style="font-size: 11px;text-align:center">&nbsp;PART NO</th>
            <th style="font-size: 11px;text-align:center">&nbsp;SEQUENCE</th>
            <th style="font-size: 11px;text-align:center">&nbsp;SEQUENCE TOTAL</th>
            <th style="font-size: 11px;text-align:center">&nbsp;KANBAN QTY</th>
            <th style="font-size: 11px;text-align:center">&nbsp;KANBAN QTY TOTAL</th>
            <th style="font-size: 11px;text-align:center">&nbsp;CREATED BY</th>
            <th style="font-size: 11px;text-align:center">&nbsp;CREATION DATE</th>
        </tr>
        @foreach ($data as $a => $q)
            <tr>
                <td style="font-size: 11px;text-align:center;">{{ $loop->iteration }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['ekanban_no'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['item_code'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['part_no'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['seq'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['seq_tot'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['kanban_qty'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['kanban_qty_tot'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['created_by'] }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q['creation_date'] }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
