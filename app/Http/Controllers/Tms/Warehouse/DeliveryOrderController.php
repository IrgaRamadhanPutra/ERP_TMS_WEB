<?php

namespace App\Http\Controllers\TMS\Warehouse;

// Laravel Libraries
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DataTables;

// Classes
use App\Classes\ButtonBuilder As ButtonBuilder;

// Controllers
use App\Http\Controllers\RolePermissionController As RolePermissionControl;

// Models
use App\Models\User_Role As UserRole;
use App\Models\Dbtbs\Delivery_Order;
use App\Models\Dbtbs\Customer;
use App\Models\Dbtbs\Schedule_Sales_Order;
use App\Models\Dbtbs\Sys_Number;

class DeliveryOrderController extends Controller
{
    public function index(){

        return view('tms.warehouse.delivery_order.index');
    }

    public function getDatatablesDO(){
        $data_do = Delivery_Order::groupBy('do_no')
        ->select('do_no','cust_name','dn_no','po_no','ref_no');
        $data = Delivery_Order::groupBy('do_no')
        ->select('do_no');
        $count = $data->count();
        return Datatables::of($data_do)
        ->skipTotalRecords()
        ->setTotalRecords($count)
        ->make(true);
    }

    public function searchDatatablesDO($search_by,$string){
        $value = str_replace("<islashi>","/",$string);
        $x  = strlen($value);
        if($search_by == 'do_no' && $x == 8){
            $op = '=';
            $val = $value;
        }else {
            $op = 'LIKE';
            $val = '%'.$value.'%';
        }
        $data_do = Delivery_Order::where($search_by,$op,$val)
        ->groupBy('do_no')
        ->select('do_no','cust_name','dn_no','po_no','ref_no');
        $data = Delivery_Order::groupBy('do_no')
        ->select('do_no');
        $count = $data->count();
        return Datatables::of($data_do)
        ->skipTotalRecords()
        ->setTotalRecords($count)
        ->make(true);
    }

    public function getAllCustomer(Request $request){
        $search = $request->get('term');
        $data = Customer::select('CustomerCode_eKanban','CustomerName')
        ->where('status_data','ACTIVE')
        ->where('CustomerCode_eKanban','LIKE','%'.$search.'%')
        ->orWhere('Customername','LIKE','%'.$search.'%')->get();
        $response = array();
        foreach ($data as $query)
        {
           // $response[] = ['customer_code' => $query->CustomerCode_eKanban, 'value' => $query->CustomerCode_eKanban.' - '.$query->CustomerName, 'customer_name' => $query->CustomerName ]; //you can take custom values as you want
            $response[] = array("label"=> $query->CustomerCode_eKanban.' - '.$query->CustomerName,"customer_code"=>$query->CustomerCode_eKanban);
        }
        echo json_encode($response);
        exit;
    
    }

    public function getDataSSOforDOHeader(Request $request){
        $search = $request->find_dt;
        $data = Schedule_Sales_Order::where('db_tbs.entry_sso_tbl.sso_header',$search)
        ->leftJoin('db_tbs.entry_so_tbl','db_tbs.entry_sso_tbl.so_header','=','db_tbs.entry_so_tbl.so_header')
        ->leftJoin('ekanban.ekanban_customermaster','db_tbs.entry_so_tbl.cust_id','=','ekanban.ekanban_customermaster.CustomerCode_eKanban')
        ->leftJoin('db_tbs.sys_do_address',function($join){$join->on('db_tbs.entry_so_tbl.cust_id','=','db_tbs.sys_do_address.cust_code');$join->on('db_tbs.entry_so_tbl.do_address','=','db_tbs.sys_do_address.id_do');})
        ->select('db_tbs.entry_so_tbl.so_header as so_header','db_tbs.entry_so_tbl.cust_id as cust_id','ekanban.ekanban_customermaster.CustomerName as customer','db_tbs.entry_so_tbl.po_no as po_no',
                    'db_tbs.entry_sso_tbl.dn_no as dn_no',
                    'db_tbs.sys_do_address.do_addr1 as Address1','db_tbs.sys_do_address.do_addr2 as Address2','db_tbs.sys_do_address.do_addr3 as Address3',
                    'db_tbs.sys_do_address.do_addr4 as Address4','db_tbs.sys_do_address.id_do','db_tbs.entry_so_tbl.branch as branch','db_tbs.entry_sso_tbl.closed_date',
                    'db_tbs.entry_so_tbl.warehouse as wh','db_tbs.entry_sso_tbl.sso_header as sso_header','ekanban.ekanban_customermaster.Cus_Group as gr_customer','db_tbs.entry_so_tbl.do_address as id_do_addr')
        ->where('db_tbs.entry_sso_tbl.active_cls','1')
        ->groupBy('db_tbs.entry_so_tbl.so_header')
        ->get();
        $response = array();
        foreach ($data as $query)
        {
           // $response[] = ['customer_code' => $query->CustomerCode_eKanban, 'value' => $query->CustomerCode_eKanban.' - '.$query->CustomerName, 'customer_name' => $query->CustomerName ]; //you can take custom values as you want
           // $response = array('cust_id'=>$query->cust_id,'customer'=> $query->CustomerName, 'po'=>$query->po_no);
           $response[] = [
                        'so_header' => $query->so_header,'cust_id' => $query->cust_id,
                        'customer' => $query->customer,'po' => $query->po_no,'dn_no' => $query->dn_no,
                        'address1'=> $query->Address1, 'address2'=> $query->Address2, 'address3'=> $query->Address3,
                        'address4'=> $query->Address4,'branch'=> $query->branch,'wh'=> $query->wh,'sso_header'=> $query->sso_header,'cust_group'=> $query->gr_customer,'id_do_addr'=> $query->id_do_addr,'closed_date'=> $query->closed_date
                        ];
        }
        echo json_encode($response);
        exit;
    
    }


    public function getDataSSOforDODetail(Request $request){
        $search = $request->find_dt_dtl;
        $data_do = Schedule_Sales_Order::where('db_tbs.entry_sso_tbl.sso_header',$search)
        //->whereNull('db_tbs.entry_sso_tbl.closed_date')
        ->groupBy('db_tbs.entry_sso_tbl.item_code')
        ->leftJoin('db_tbs.item','db_tbs.entry_sso_tbl.item_code','=','db_tbs.item.itemcode')
        ->leftJoin('db_tbs.entry_so_tbl',function($join){$join->on('db_tbs.entry_sso_tbl.so_header','=','db_tbs.entry_so_tbl.so_header');$join->on('db_tbs.entry_sso_tbl.item_code','=','db_tbs.entry_so_tbl.item_code');})
        ->leftJoin('db_tbs.entry_do_tbl',function($join){$join->on('db_tbs.entry_sso_tbl.sso_header','=','db_tbs.entry_do_tbl.sso_no');$join->on('db_tbs.entry_sso_tbl.item_code','=','db_tbs.entry_do_tbl.item_code');})
        ->select(['db_tbs.entry_sso_tbl.dn_no','db_tbs.item.part_no','db_tbs.entry_sso_tbl.item_code','db_tbs.item.unit','db_tbs.item.descript1','db_tbs.entry_so_tbl.qty_so',
        'db_tbs.entry_sso_tbl.sso_header as sso_no','db_tbs.entry_so_tbl.so_header as so_no','db_tbs.item.descript','db_tbs.entry_sso_tbl.qty_sso as qty_sso',
       // DB::raw('concat(db_tbs.entry_sso_tbl.sso_header,LPAD(db_tbs.entry_sso_tbl.sso_detail,3,0)) as sso_no,concat(db_tbs.entry_so_tbl.so_header,LPAD(db_tbs.entry_so_tbl.so_detail,3,0)) as so_no,0 as qty_tag,sum(db_tbs.entry_do_tbl.quantity) as qty_sj,round(sum(db_tbs.entry_sso_tbl.qty_sso),2) as qty_sso'),'db_tbs.item.descript'])
        DB::raw('IFNULL(sum(db_tbs.entry_do_tbl.quantity),0) as qty_sj, date(db_tbs.entry_sso_tbl.closed_date) as closed_date')])
        ->get();
        $response = array();
        foreach ($data_do  as $query)
        {
           $response[] = [
               'dn_no' => $query->dn_no,
               'item_code' => $query->item_code,
               'part_no' => $query->part_no,
               'sso_no' => $query->sso_no,
               'so_no' => $query->so_no,
               'qty_sj' => $query->qty_sj,
               'qty_so' => $query->qty_so,
               'qty_sso' => $query->qty_sso,
               'unit' => $query->unit,
               'part_name' => $query->descript,
               'model' => $query->descript1
            ];
        }
        echo json_encode($response);
        exit;
    }

    public function getDataDeliveryOrder(Request $request){
            $do = $request->find_do;
            $data = Delivery_Order::where('do_no',$do)
            ->leftJoin('db_tbs.item','item_code','=','db_tbs.item.itemcode')
            ->leftJoin('db_tbs.sys_do_address',function($join){$join->on('db_tbs.entry_do_tbl.cust_id','=','db_tbs.sys_do_address.cust_code');$join->on('db_tbs.entry_do_tbl.do_address','=','db_tbs.sys_do_address.id_do');})
            ->leftJoin('db_tbs.entry_sso_tbl',function($join){$join->on('db_tbs.entry_do_tbl.sso_no','=','db_tbs.entry_sso_tbl.sso_header');$join->on('db_tbs.entry_do_tbl.item_code','=','db_tbs.entry_sso_tbl.item_code');})
            ->leftJoin('db_tbs.entry_so_tbl',function($join){$join->on('db_tbs.entry_do_tbl.so_no','=','db_tbs.entry_so_tbl.so_header');$join->on('db_tbs.entry_do_tbl.item_code','=','db_tbs.entry_so_tbl.item_code');})
            ->select(['db_tbs.entry_do_tbl.so_no','db_tbs.entry_do_tbl.sso_no','db_tbs.entry_do_tbl.cust_id','db_tbs.entry_do_tbl.delivery_date','db_tbs.entry_do_tbl.period','db_tbs.entry_do_tbl.do_no','db_tbs.sys_do_address.cust_name','db_tbs.sys_do_address.do_addr1 as Address1','db_tbs.sys_do_address.do_addr2 as Address2','db_tbs.sys_do_address.do_addr3 as Address3',
            'db_tbs.sys_do_address.do_addr4 as Address4','db_tbs.entry_do_tbl.row_no','db_tbs.entry_do_tbl.item_code','db_tbs.item.part_no as part_no','db_tbs.item.descript as part_name','db_tbs.item.descript1 as model','db_tbs.item.unit as unit','db_tbs.item.fac_unit as fac_unit',
            'db_tbs.entry_so_tbl.qty_so as qty_so','db_tbs.entry_sso_tbl.qty_sso as qty_sso','db_tbs.entry_do_tbl.quantity','db_tbs.entry_do_tbl.branch','db_tbs.entry_do_tbl.warehouse','db_tbs.entry_do_tbl.dn_no','db_tbs.entry_do_tbl.po_no','db_tbs.entry_do_tbl.ref_no','db_tbs.entry_do_tbl.remark',
            'db_tbs.entry_do_tbl.delivery_date','db_tbs.entry_do_tbl.period','db_tbs.entry_do_tbl.invoice',DB::raw('date(db_tbs.entry_do_tbl.printed_date) as printed,date(db_tbs.entry_do_tbl.posted_date) as posted,date(db_tbs.entry_do_tbl.finished_date) as finished,date(db_tbs.entry_do_tbl.voided_date) as voided')])
            ->get();
            $response = array();
            foreach ($data as $query)
            {
               $response[] = ['so_no' => $query->so_no,'sso_no' => $query->sso_no,'custcode' => $query->cust_id,
               'written' => $query->delivery_date,'periode' => $query->period,'address1'=> $query->Address1,
               'address2'=> $query->Address2, 'address3'=> $query->Address3,'address4'=> $query->Address4,
               'nu'=> $query->row_no,'item_code' => $query->item_code,'part_no' => $query->part_no,
               'part_name' => $query->part_name,'model' => $query->model,'unit' => $query->unit,
               'fac_unit' => $query->fac_unit,'qty_so' => $query->qty_so,'qty_sso' => $query->qty_sso,
               'qty_sj' => $query->quantity,'branch' => $query->branch,'wh' => $query->warehouse,
               'dn_no' => $query->dn_no,'po_no' => $query->po_no,'ref_no' => $query->ref_no,'remark' => $query->remark,
               'cust_name' => $query->cust_name,'delivery' => $query->delivery_date,'period' => $query->period,'printed' => $query->printed,
               'posted' => $query->posted,'finished' => $query->finished,'voided' => $query->voided,'invoice' => $query->invoice];
            }
            echo json_encode($response);
            exit;
    }

    public function GetDoNo(){
            $reference = Sys_Number::select(DB::raw('concat(right(year(NOW()),2),DATE_FORMAT(NOW(),"%m")) as ref'))
            ->limit('1')
            ->get();
            $dono_sysno = Sys_number::where('label','DO NUMBER')
            ->select('contents')
            ->get();
            $a = substr($dono_sysno[0]->contents,0,4); //4 digit do no dari sys number ex: 2007 | 20 : 2 Digit Terakhir dari Tahun 2020 | 07 " 2 digit dari Bulan pada tahun tersebut
            $b = $reference[0]->ref; //4 digit dari datetime diambil sebagai validasi 4 digit no dari sys number ex: 2007 | 20 : 2 Digit Terakhir dari Tahun 2020 | 07 " 2 digit dari Bulan pada tahun tersebut
            if ($a == $b){ //Jika 4 digit dari sys_number & date sama
                //do no dari sysnumber ditambahkan 1
                 $cek_dono = $dono_sysno[0]->contents + 1;
                //kemudian di cek di tabel do hdr
                 $cek_dohdr = Delivery_Order::where('do_no',$cek_dono)
                 ->select(['do_no'])
                 ->get();  
                if ($cek_dohdr->isEmpty()){ //jika result dari cek_dohdr null(kosong)
                    $do_no = $cek_dono;
                    return $do_no;
                }else{
                    do{
                        $cek_dono++;
                        $cek_dohdr = Delivery_Order::where('do_no',$cek_dono)
                        ->select(['do_no'])
                        ->get();           
                    }while (!$cek_dohdr->isEmpty()); //lopping sampai tabel do hdr mendapatkan value null
                    $do_no = $cek_dono;
                    return $do_no;
                }
            } else {
                //buat baru do no dengan ref ditambah 0001 dan cek di do no
                $cek_dono  = $b;
                $cek_dono  .= '0001';
                $cek_dohdr = Delivery_Order::where('do_no',$cek_dono)
                ->select(['do_no'])
                ->get();  
                if ($cek_dohdr->isEmpty()){
                    $do_no = $cek_dono;
                    return $do_no;
                }else{
                    do{
                        $cek_dono++;
                        $cek_dohdr = Delivery_Order::where('do_no',$cek_dono)
                        ->select(['do_no'])
                        ->get();           
                    }while (!$cek_dohdr->isEmpty());
                    $do_no = $cek_dono;
                    return $do_no;
                }
            }  
      

    }


    public function saveDataDeliveryOrder(Request $request){
    
				$dono = $this->GetDoNo();
				$data = json_decode($request->getContent(), true); 
				$len = count($data);      
	  
				for ($i = 0;$i < $len ; $i++){

					$add_dtl                    = new Delivery_Order;
					$add_dtl->do_no 			= $dono;
					$add_dtl->row_no			= $data[$i]['nu'];             //str_pad($i, 3, "0", STR_PAD_LEFT);
					$add_dtl->item_code			= $data[$i]['itemcode'];
					$add_dtl->quantity			= $data[$i]['qty_sj'];
					$add_dtl->unit				= $data[$i]['unit'];
					$add_dtl->so_no				= $data[$i]['so_no'];
					$add_dtl->sso_no			= $data[$i]['sso_no'];
					$add_dtl->ref_no			= $data[$i]['ref_no'];
					$add_dtl->po_no				= $data[0]['po_no'];
					$add_dtl->dn_no				= $data[0]['dn_no'];
					$add_dtl->period			= $data[0]['period'];
					$add_dtl->cust_id			= $data[0]['custcode'];
					$add_dtl->do_address		= $data[0]['id_do'];
					$add_dtl->cust_name			= $data[0]['cust_name'];
					$add_dtl->source			= ""; 
					$add_dtl->id_driver			= ""; 
					$add_dtl->remark			= $data[$i]['remark'];
					$add_dtl->branch			= $data[$i]['pl'];
					$add_dtl->warehouse			= $data[$i]['wh'];
					$add_dtl->delivery_date		= $data[0]['date_do'];
					$add_dtl->created_by		= Auth::user()->FullName;
					$add_dtl->created_date		= date(now());
					$add_dtl->do_trans			= 0;
					$add_dtl->save();  
                                       
            }
            return response()->json([
                'dono' => $dono,'sts' => 1
            ]);
    }

    public function voidDataDeliveryOrder($dono){
        $data = Delivery_Order::where('do_no',$dono)->delete();
    }

    
}
