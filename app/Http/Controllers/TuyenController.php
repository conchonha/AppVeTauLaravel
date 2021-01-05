<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tuyen;

class TuyenController extends Controller
{
    public function getAllTuyen(){
    	$table = Tuyen::join('ga','Tuyen.GADI','ga.TENGA')->get();
    	return response()->json($table);
    }
}
