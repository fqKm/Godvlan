<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionAddRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionResources;
use App\Models\Transaction;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function add(TransactionAddRequest $request):TransactionResources{
        $data=$request->validated();
        $user=Auth::user();

        $transaction=new Transaction($data);
        $transaction->user_id= $user->id;
        $transaction->save();

        return new TransactionResources($transaction);
    }

    public function index(Request $request):JsonResponse{
        $user = Auth::user();
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 15);

        // Optional filters
        $jenisTransaksi = $request->query('jenis_transaksi'); // 'pemasukan' or 'pengeluaran'
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Transaction::where('user_id', $user->id);

        // Apply filters if provided
        if ($jenisTransaksi) {
            $query->where('jenisTransaksi', $jenisTransaksi);
        }

        if ($startDate) {
            $query->whereDate('tanggalTransaksi', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggalTransaksi', '<=', $endDate);
        }

        // Order by date (newest first) and paginate
        $transactions = $query->orderBy('tanggalTransaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => TransactionResources::collection($transactions->items()),
            'paging' => [
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'last_page' => $transactions->lastPage(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
                'has_next_page' => $transactions->hasMorePages(),
                'has_prev_page' => $transactions->currentPage() > 1,
            ],
            'filters' => [
                'jenis_transaksi' => $jenisTransaksi,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        ]);
    }

    public function show(int $id):TransactionResources{
        $user = Auth::user();
        $transactions= Transaction::where('id',$id)->where('user_id',$user->id)->first();
        if (!$transactions){
            throw new HttpResponseException(response()->json([
                'error'=>[
                    "message"=>[
                        "Message not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new TransactionResources($transactions);
    }

    public function update(int $id,TransactionUpdateRequest $request):TransactionResources
    {
        $user= Auth::user();
        $transactions= Transaction::where('id',$id)->where('user_id',$user->id)->first();
        if (!$transactions){
            throw new HttpResponseException(response()->json([
                'error'=>[
                    "message"=>[
                        "Message not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        $data=$request->validated();
        $transactions->fill($data);
        $transactions->save();
        return new TransactionResources($transactions);
    }

}
