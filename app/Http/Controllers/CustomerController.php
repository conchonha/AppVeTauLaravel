<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function register(Request $request){
    	$validator = validator::make($request->all(), [
	        'fullName' => 'required',
	        'identityCard' => 'required|digits:9|unique:khachhang,SOCMND',
	        'email' => 'required|email|unique:khachhang,EMAIL',
	        'phoneNumber' => 'required|regex:/(0)[0-9]/|not_regex:/[a-z]/|digits:10|unique:khachhang,SODT',
	        'objectCode' => 'required|numeric',
    		],
     		[ 
     		'fullName.required' => 'Malformed name',
     		'identityCard.digits' => 'IdentityCardis not correct format',
     		'phoneNumber.digits' => 'Phonenumber not correct format',
     		]
   		 );

    	if ($validator->fails()){
		       return $this->respondWithJson(300,$validator->errors(),[],0);
		}else{
			   $customer = new Customer();
			   $customer->TENKH = $request->fullName;
			   $customer->SOCMND = $request->identityCard;
			   $customer->EMAIL = $request->email;
			   $customer->SODT = $request->phoneNumber;
			   $customer->MADOITUONG = $request->objectCode;
			   $customer->save();
			return $this->respondWithJson(200,null,$customer,1);
	    }
    }

    public function checkEmail(Request $request){
    	$validator = validator::make($request->all(), [
	        'email' => 'required|email'
    		]
   		 );

    	if ($validator->fails()){
		       return $this->respondWithJson(300,"Email invalid",null,0);
		}else{
			   $table = Customer::where('EMAIL',$request->email)->get();
			   if($table->count() > 0){
			   		return $this->respondWithJson(200,"Successfully",$table[0],0);
			   }else{
			   		return $this->respondWithJson(300,"Email does not exist",null,0);
			   }
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
