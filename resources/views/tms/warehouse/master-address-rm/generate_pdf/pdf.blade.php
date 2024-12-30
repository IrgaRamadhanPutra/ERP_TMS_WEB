<!DOCTYPE html>
<html>

<head>
    <title></title>
</head>
<style>
    table {
        max-width: 3500;
        /* width: 100%; */
    }
</style>
{{-- <table style="border-collapse: collapse; border: none; border-spacing: 0px;">
    <tr>
        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">

        </td>
        <td colspan="3" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            PT TRIMITA CHITRAHASTA
        </td>
        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            CODE
        </td>
        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            CUST
        </td>
        <td rowspan="7" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
        </td>
    </tr>
    <tr>
        <td rowspan="5" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            <div style="text-align: center;">
                <img src="assets/img/avatar/tch2.png" height="50px" width="60px" alt="">
            </div>
        </td>
        <td rowspan="2" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 2pt; padding-left: 2pt; padding-top: 5px; padding-bottom: 5px;">
            CHUTER ADDRESS
        </td>
        <td rowspan="2" colspan="2" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 2pt; padding-left: 2pt; padding-top: 5px; padding-bottom: 5px; text-align: center;">
            <h3 style="margin: 0;">{{ $data['chuter_address'] }}</h3>
        </td>

        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            IN HOUSE
        </td>
        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            {{ $data['cust_code'] }} <!-- Ganti dengan cust_code dari data -->
        </td>
    </tr>
    <tr>
        <td rowspan="4" colspan="2" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 10pt; text-align: center;">
            <img src="data:image/png;base64,{{ base64_encode($qrcode) }}" alt="QR Code In" style="width: 100px;">
        </td>
    </tr>

    <tr>
        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            PART NAME
        </td>
        <td colspan="2" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            {{ $data['part_name'] }} <!-- Ganti dengan part_name dari data -->
        </td>
    </tr>
    <tr>
        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            PART NO
        </td>
        <td colspan="2" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            {{ $data['part_no'] }} <!-- Ganti dengan part_no dari data -->
        </td>
    </tr>
    <tr>
        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            ITEM CODE
        </td>
        <td colspan="2" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            {{ $data['itemcode'] }} <!-- Ganti dengan itemcode dari data -->
        </td>
    </tr>
    <tr>
        <td style="border-width: 1px; border-style: solid; font-size: 10px; border-color: rgb(0, 0, 0); padding-right: 2pt; padding-left: 2pt;">
            {{ $date }} <!-- Ganti dengan tanggal -->
        </td>

        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            SIZE
        </td>
        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            {{ $data['size'] }} <!-- Ganti dengan size dari data -->
        </td>
        <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            SUPPLIER
        </td>
        <td colspan="2" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding-right: 3pt; padding-left: 3pt;">
            {{ $data['supplier'] }} <!-- Ganti dengan supplier dari data -->
        </td>
    </tr>
</table> --}}
{{-- <table style="border-collapse: collapse; border: none; border-spacing: 0px;"> --}}
{{-- <table style="border-collapse: collapse; border: none; border-spacing: 0px; height: 130px;">
        <table style="border-collapse: collapse; border: none; border-spacing: 0px;"> --}}
<div style="max-width: 3608px; margin: auto;">
    <table style="border-collapse: collapse; border: none; border-spacing: 0px; width: 100%;">
        <tr>
            <td rowspan="2" style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding: 5pt;">
                <div style="text-align: center;">
                    <img src="assets/img/avatar/tch2.png" height="60px" width="100px" alt="">
                </div>
            </td>
            <td colspan="3"
                style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); text-align: center; padding: 5pt;">
                PT TRIMITRA CHITRAHASTA
            </td>
        </tr>
        <tr>
            <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding: 5pt;">
                CHUTER&nbsp;
                <br>
                ADDRESS
            </td>
            <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding: 5pt;">
                <h2 style="margin: 5px 0; font-size: 50px; overflow: hidden; white-space: nowrap;">
                    {{ $data['chuter_address'] }}</h2>
            </td>
            <td rowspan="3"
                style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding: 5pt;">

                <div style="text-align: center;">
                    <img src="data:image/png;base64,{{ base64_encode($qrcode) }}" alt="QR Code In"
                        style="width: 120px;">
                </div>
            </td>
        </tr>
        <tr>
            <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding: 5pt;">
                Printed Date
            </td>
            <td rowspan="2"
                style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding: 5pt;">
                PARTNAME
            </td>
            <td rowspan="2"
                style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding: 5pt;">
                <h1 style="margin: 5px 0; font-size: 50px; overflow: hidden; word-wrap: break-word;">
                    {{ $data['part_name'] }}</h1>
            </td>
        </tr>
        <tr>
            <td style="border-width: 1px; border-style: solid; border-color: rgb(0, 0, 0); padding: 5pt;">
                {{ $date }}
            </td>
        </tr>
    </table>
</div>
