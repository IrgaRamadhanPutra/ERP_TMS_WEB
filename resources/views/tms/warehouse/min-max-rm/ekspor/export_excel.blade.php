<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
</head>

<body>
    <table>
        <tr>
            <td colspan="6" style="font-size:15px;"><b>Master Min Max Raw Material </b></td>
        </tr>
        <tr>
            <td colspan="6">Date: {{ $date }}</td>
        </tr>
    </table>

    <br>

    <table border="1" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th style="font-size: 11px;">NO</th>
                <th style="font-size: 11px;">CHUTTER ADDRESS</th>
                <th style="font-size: 11px;">ITEMCODE</th>
                <th style="font-size: 11px;">PART NO</th>
                <th style="font-size: 11px;">PART NAME</th>
                <th style="font-size: 11px;">PART TYPE</th>
                <th style="font-size: 11px;">CUST CODE</th>
                <th style="font-size: 11px;">UNIT</th>
                <th style="font-size: 11px;">MIN</th>
                <th style="font-size: 11px;">MAX</th>
                <th style="font-size: 11px;">STOCK</th>
                <th style="font-size: 11px;">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $row)
                <tr>
                    <td style="font-size: 11px;">{{ $key + 1 }}</td>
                    <td style="font-size: 11px;">{{ $row['chutter_address'] }}</td>
                    <td style="font-size: 11px;">{{ $row['itemcode'] }}</td>
                    <td style="font-size: 11px;">{{ $row['part_number'] }}</td>
                    <td style="font-size: 11px;">{{ $row['part_name'] }}</td>
                    <td style="font-size: 11px;">{{ $row['part_type'] }}</td>
                    <td style="font-size: 11px;">{{ $row['cust_code'] }}</td>
                    <td style="font-size: 11px;">{{ $row['satuan'] }}</td>
                    <td style="font-size: 11px;">{{ $row['min'] }}</td>
                    <td style="font-size: 11px;">{{ $row['max'] }}</td>
                    <td style="font-size: 11px;">{{ $row['balance'] }}</td>
                    <td
                        style="font-size: 11px; text-align: center;
                        @if ($row['min'] > $row['balance']) background-color: red;
                        @elseif ($row['max'] < $row['balance']) background-color: yellow;
                        @else background-color: green; @endif">
                        @if ($row['min'] > $row['balance'])
                            Kritis
                        @elseif ($row['max'] < $row['balance'])
                            Over
                        @else
                            OK
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</body>

</html>
