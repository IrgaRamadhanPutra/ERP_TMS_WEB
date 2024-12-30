<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Master Min Max</title>
</head>

<body>
    <table>
        <tr>
            <td colspan="6" style="font-size:15px;"><b>Master Min Max</b></td>
        </tr>
        <tr>
            <td colspan="6">Date : {{ $date }}</td>
        </tr>
    </table>


    <br>

    <table>
        <tr>
            <th style="font-size: 11px;">&nbsp;NO</th>
            <th style="font-size: 11px;">&nbsp;CHUTTER ADDRESS</th>
            <th style="font-size: 11px;text-align:center">&nbsp;ITEMCODE </th>
            <th style="font-size: 11px;text-align:center">&nbsp;PART NO</th>
            <th style="font-size: 11px;text-align:center">&nbsp;PART NAME </th>
            <th style="font-size: 11px;text-align:center">&nbsp;PART TYPE</th>
            <th style="font-size: 11px;text-align:center">&nbsp;LOT</th>
            <th style="font-size: 11px;text-align:center">&nbsp;MIN</th>
            <th style="font-size: 11px;text-align:center">&nbsp;MAX</th>
            <th style="font-size: 11px">&nbsp;STOCK</th>
            <th style="font-size: 11px">&nbsp;STATUS</th>
        </tr>

        @foreach ($data as $a => $q)
            <tr>
                <td style="font-size: 11px;text-align:left;">{{ $loop->iteration }}</td>
                <td style="font-size: 11px;text-align:left;">{{ $q->chutter_address }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q->itemcode }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q->part_number }}</td>
                <td style="font-size: 11px;text-align:left;">{{ $q->part_name }} </td>
                <td style="font-size: 11px;text-align:center;">{{ $q->part_type }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q->lot }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q->min }}</td>
                <td style="font-size: 11px;text-align:center;">{{ $q->max }}</td>
                <td style="font-size: 11px;text-align:center">{{ $q->balance }}</td>
                <td
                    style="font-size: 11px;text-align:center;
                       @if ($q->min > $q->balance) background-color: red;
                       @elseif($q->max < $q->balance) background-color: yellow;
                       @else background-color: green; @endif">
                    @if ($q->min > $q->balance)
                        Kritis
                    @elseif($q->max < $q->balance)
                        Over
                    @else
                        OK
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

</body>

</html>
