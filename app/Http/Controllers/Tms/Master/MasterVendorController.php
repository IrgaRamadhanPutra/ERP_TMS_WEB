<?php

namespace App\Http\Controllers\Tms\Master;

use Illuminate\Http\Request;
use App\Models\Dbtbs\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use DataTables;
use Validator;
use DB;
//use Redirect;


class MasterVendorController extends Controller
{   
    public function index() 
    {
        return view('tms.master.mastervendor');
    }
    

    /*Load datatable vendor */
    public function datavendor() 
    {
        $dt_vendor = Vendor::all();
        return DataTables::of($dt_vendor)
            ->addColumn('action', function($row){
                //return view('tms.modals.actionvendor');
                //return view('btnblade.php')
            //$btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="View" id="view_vendor" <i class="fa fa-eye viewVendor"></i></a>';
            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" title="Edit" id="edit_vendor" <i class="fa fa-edit editVendor"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" id="del_vendor" <i class="fa fa-trash-o deleteVendor"></i></a>';
            return $btn;
        })->rawColumns(['action'])
        ->make(true);
    }

    //POST DATA VENDOR
   public function postdata(Request $request) //sama dengan store
   {
       Vendor::updateOrCreate(
           [
               'VENDCODE'   => $request->vendcode,
               'INDUSTRY'   => $request->industry,
               'COMPANY'    => $request->company,
               'CONTACT'    => $request->contact,
               'ADDRESS1'   => $request->address1,
               'ADDRESS2'   => $request->address2,
               'PHONE'      => $request->phone,
               'FAX'        => $request->fax,
               'HP'         => $request->hp,
               'EMAIL'      => $request->email,
               'GLAP'       => $request->glap,
               'NPWP'       => $request->npwp,
               'TERMOFPAY'  => $request->termpay,
               'TAXRATE'    => $request->rate
           ]
           );

           return response()->json(
               [
                   'success' => true,
                   'message' => 'Data Berhasil'
               ]
               );
   }
    


   //EDIT DATA VENDOR
   /*
   public function editdata($id)
   {
       $data        = Vendor::where('VENDCODE', $id)->first();
       $det_vend    = Vendor::select(
                        'VENDCODE', 'INDUSTRY', 'COMPANY', 'CONTACT', 
                        'ADDRESS1', 'ADDRESS2', 'ADDRESS3', 'PHONE',
                        'FAX', 'HP', 'EMAIL', 'GLAP', 'NPWP',
                        'TERMOFPAY', 'TAXRATE'
                        )
                    ->where('VENDCODE', '=', $id)
                    ->get();
        $output     =[
                    'header' => $data,
                    'detail' => $det_vend
        ];

        return response()->json($output);
   }*/
   
   public function editdata(Request $request, $id)
   {
       Vendor::updateOrCreate(
           [
               'id' => $id
           ],
           [
               'VENDCODE'   => $request-> VENDCODE,
               'INDUSTRY'   => $request-> INDUSTRY,
               'COMPANY'    => $request-> COMPANY,
               'CONTACT'    => $request-> CONTACT,
               'ADDRESS1'   => $request-> ADDRESS1,
               'ADDRESS2'   => $request-> ADDRESS2,
               'PHONE'      => $request-> PHONE,
               'FAX'        => $request-> FAX,
               'HP'         => $request-> HP,
               'EMAIL'      => $request-> EMAIL,
               'GLAP'       => $request-> GLAP,
               'NPWP'       => $request-> NPWP,
               'TERMOFPAY'  => $request-> TERMOFPAY,
               'TAXRATE'    => $request-> TAXRATE
           ]
           );

           return response()->json(['success' => true]);
   } 



    //DELETE DATA VENDOR      
    public function deletedata(Request $request)
    {   
        Vendor::find($request->id)->delete();
        return response()->json(
            [
                'success' => true,
                'message' => 'Data Berhasil'
            ]
            );
       
    } 
    

    
   


    

//end tag master vendor controller   
}
