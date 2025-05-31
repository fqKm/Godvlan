<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionAddRequest;
use App\Http\Resources\TransactionResources;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request){
        $page=$request->query('page',1);
    }
    public function add(TransactionAddRequest $request):TransactionResources{
        $data=$request->validated();
        $user=Auth::user();

        $transaction=new Transaction($data);
        $transaction->user_id= $user->id;
        $transaction->save();

        return new TransactionResources($transaction);
    }

    public function get(){

    }

    public function delete(){
    }
}
