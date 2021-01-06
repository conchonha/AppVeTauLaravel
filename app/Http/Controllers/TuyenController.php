<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tuyen;
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
