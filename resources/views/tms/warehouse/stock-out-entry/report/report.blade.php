<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

    <h4 style="text-align: left"><b>PT TRIMITRA CHITRAHASTA</b></h4>
	<center><h4 style="margin-bottom: 40px"><b>BUKTI PENGELUARAN BARANG</b></h4></center>
	<br>
	<br>
	<table class="table">
		
		<tbody>
			<tr>
				<td style="font-size: 14px"><b>REMARK</b></td>
				<td style="width: 1px">:</td>
			    <td style="font-size: 14px">{{ $data2->remark_header }}</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td> 
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
                <td></td> 
                <td></td> 
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
                <td></td> 
                <td></td> 
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
                <td></td> 
                <td></td> 
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
                <td></td>
                <td></td>
				<td></td>
                <td></td>
                <td></td>
                <td></td>
				<td style="font-size: 14px" ><b>S.P.K</b></td>
				<td style="width: 30px">:</td>
               <td style="font-size: 14px">{{ $data2->out_no }}</td>


			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				{{-- <td></td>
					<td></td> --}}
				{{-- <td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td> --}}
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td style="font-size: 14px"><b>DEPARTMENT</b></td>
				<td style="width: 30px">:</td>
				<td style="font-size: 14px">-</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td> 
                <td></td>
                <td></td>
				<td></td> 
                <td></td>
                <td></td>
				<td></td> 
                <td></td>
                <td></td>
				<td></td> 
                <td></td>
                <td></td>
				<td></td> 
                <td></td>
                <td></td>
                <td></td>
				<td></td> 
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
				<td></td>
                <td></td> 
                <td></td>
				<td></td>
                <td></td> 
                <td></td>
				<td></td>
				<td></td> 
				<td style="font-size: 14px"><b>DATE</b></td>
				<td style="width: 20px">:</td>
				<td style="font-size: 14px">{{ \Carbon\Carbon::parse($data2->written)->format('d/m/Y') }} </td>
			</tr>

		</tbody>
		<br>
	</table>
	<table class="table table-striped table-bordered" cellpadding="3" cellspacing="0" border="1" width="100%">
		<thead>
			<tr style="height: 21px;">
				<th style="font-size: 14px">&nbsp;No</th>
				<th style="font-size: 14px">&nbsp;Itemcode</th>
				<th style="font-size: 14px">&nbsp;Part No</th>
                <th style="font-size: 14px">&nbsp;Description/Part Name</th>
                <th style="font-size: 14px">&nbsp;Type</th>
                <th style="font-size: 14px">&nbsp;Quantity</th>
			</tr>
		</thead>
		<tbody style="text-align: left">
            @php $no = 1; @endphp
            @foreach($data1 as $row)
			<tr>
				<td style="font-size: 14px">{{ $no++ }}</td>
                <td style="font-size: 14px">{{ $row->itemcode  }}</td>
				<td style="font-size: 14px">{{ $row->part_no  }}</td>
                <td style="font-size: 14px">{{ $row->descript  }}</td>
                <td style="font-size: 14px">{{ $row->types  }}</td>
                <td style="font-size: 14px">{{ $row->quantity  }}.00 &nbsp;&nbsp; {{ $row->unit }}</td>
            </tr>
            @endforeach
		</tbody>
	</table>


	<p style="font-size: 13px">&nbsp;&nbsp;&nbsp;<b>MENGETAHUI</b>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>YANG MENERIMA</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>YANG MENGELUARKAN</b>
	</p>
	<br>

	<p style="font-size: 13px">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        <br>
        <br>
        <b>LD-INV-02</b>
	</p>
	
	
</body>
</html