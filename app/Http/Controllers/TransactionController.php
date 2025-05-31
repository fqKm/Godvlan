<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionAddRequest;
use App\Http\Resources\TransactionResources;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request){
        $page
    }
    public function add(TransactionAddRequest $request):TransactionResources{
        return new TransactionResources($request->user());
    }

    public function get(){

    }

    public function delete(){
    }
}
