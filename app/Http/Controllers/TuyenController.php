<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tuyen;
use App\Models\Tau;
use App\Models\Toa;
use App\Models\Vengay;
use App\Models\ThongTinDatVe;
use Validator;

class TuyenController extends Controller
{
    public function getAllTuyen(){
    	$table = Tuyen::join('ga','Tuyen.GADI','ga.TENGA')->get();
    	return response()->json($table);
    }

    public function timChuyenTau(Request $request){
    	$validator = validator::make($request->all(), [
	        'gadi' => 'required',
	        'gaden' => 'required',
	        'thoigian' => 'required',
    		]
   		 );

    	if ($validator->fails()){
		       return $this->respondWithJson(300,$validator->errors(),null,0);
		}else{
			   $table = Tuyen::select('chuyen.id','Tuyen.GADI','Tuyen.GADEN','Tuyen.updated_at','Tuyen.created_at','chuyen.MATUYEN','chuyen.MATAU','chuyen.GIODI','chuyen.GIODEN','chuyen.NGAYDEN','chuyen.NGAYDI','tau.TENTAU')->where([['Tuyen.GADI',$request->gadi],['Tuyen.GADEN',$request->gaden]])->join('chuyen','chuyen.MATUYEN','Tuyen.id')->where('chuyen.NGAYDI',$request->thoigian)->join('tau','chuyen.MATAU','tau.id')->get();
		       return $this->respondWithJson(200,null,$table,$table->count());
	    }
    }

    public function getToaTauTheoChuyen(Request $request){
    	$table = Tau::where('Tau.TENTAU',$request->tentau)->get();
    	if($table->count() > 0){
    		$idTau = $table[0]->id;
    		$table = Toa::where('Toa.tau_id',$idTau)->orderBy('Toa.TENTOA')->get();
    		return $this->respondWithJson(200,"success",$table,$table->count());
    	}else{
    		return $this->respondWithJson(300,"Lỗi không tìm thấy tàu nào tương ứng",null,0);
    	}
    }

    public function getVetau(Request $request){
    	$table = Vengay::select('Vengay.id','Vengay.ghe_id','Vengay.NGAY','Vengay.status','Vengay.updated_at','Vengay.created_at','ghe.SOGHE','ghe.MALOAIGHE','ghe.toa_id','loaighe.TENLOAIGHE','tt_giave.MACHUYEN','tt_giave.GIAVE','toa.TenToa')->join('ghe','Vengay.ghe_id','ghe.id')->where('ghe.toa_id',$request->toaid)->join('loaighe','loaighe.id','ghe.MALOAIGHE')->join('tt_giave','tt_giave.MALOAIGHE','loaighe.id')->join('toa','toa.id','ghe.toa_id')->get();
    	return $table;
    }

    public function datve(Request $request){
    	$data = json_decode( $request->arrayVe,true);
    	try {
	   		$makhach = $request->maChuyen;
	    	foreach ($data as $value) {
	    		$machuyen = $value['MACHUYEN'];
	    		$mave = $value['id'];
	    		$thanhtien = $value['GIAVE'];

	    		$table = new ThongTinDatVe();
	    		$table->MAKHACHHANG=$makhach;
	    		$table->MACHUYEN=$machuyen;
	    		$table->MAVE=$mave;
	    		$table->THANHTIEN=$thanhtien;
	    		$table->save();
	    	}

	    	return $this->respondWithJson(300,"Successfully",null,0);

		} catch (Exception $e) {

    		return $this->respondWithJson(300,"Error insert",null,0);
		}
    }

    public function respondWithJson($statuscode,$message,$data,$total)
    {
        return response()->json([
            'message' => $message,
            'statuscode' => $statuscode,
            'total' => $total,
            'data' => $data,
        ]);
    }
}
