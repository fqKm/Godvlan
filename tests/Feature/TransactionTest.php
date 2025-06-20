<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\TransactionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;
    public function testAddTransaction()
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/transaction/add',
            [
            'tanggalTransaksi'=>'2022-01-01',
            'jenisTransaksi'=>'pemasukan',
            'nominal'=>'1000000',
            'deskripsi'=>'Pemasukan dari pemilik'
            ],
            [
            "Authorization"=>'ambatublow'
            ])->assertStatus(201)
            ->assertJson([
            "data"=>[
                'tanggalTransaksi'=>'2022-01-01',
                'jenisTransaksi'=>'pemasukan',
                'nominal'=>'1000000.00',
                'deskripsi'=>'Pemasukan dari pemilik'
            ]
            ]);
    }
    public function testAddUnoathorized()
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/transaction/add',
            [
            'tanggalTransaksi'=>'2022-01-01',
            'jenisTransaksi'=>'pemasukan',
            'nominal'=>'1000000',
            'deskripsi'=>'Pemasukan dari pemilik'
            ],
            [
            "Authorization"=>'Ambatukam'
            ]
        )->assertStatus(401)
            ->assertJson([
            'error'=>[
                'message'=>[
                    "Unauthorized"
                ]
            ]
            ]);
    }
    public function testAddFailed(){
        $this->seed(UserSeeder::class);
        $this->post('/api/transaction/add',
            [
            'tanggalTransaksi'=>'2022-01-01',
            'jenisTransaksi'=>'pemasukan',
            'nominal'=>'ABS',
            'deskripsi'=>'Pemasukan dari pemilik'
            ],
            [
                "Authorization"=>'ambatublow'
            ]
        )->assertStatus(400)
            ->assertJson([
            'errors'=>[
                'nominal'=>[
                    "The nominal field must be a number."
                ]
            ]
            ]);
    }

    public function testDetailTransactionSuccess()
    {
        $this->seed([UserSeeder::class,TransactionSeeder::class]);;
        $transaction=Transaction::query()->limit(1)->first();
        $this->get('/api/transaction/history/'.$transaction->id,
            [
            "Authorization"=>'ambatublow'
            ])->assertStatus(200)
            ->assertJson([
            "data"=>[
                'tanggalTransaksi'=>'2022-01-01',
                'jenisTransaksi'=>'pemasukan',
                'nominal'=>'1000000',
                'deskripsi'=>'Pemasukan dari pemilik'
            ]
            ]);
    }

    public function testDetailTransactionNotFound()
    {
        $this->seed([UserSeeder::class,TransactionSeeder::class]);
        $transaction=Transaction::query()->limit(1)->first();
        $this->get('/api/transaction/history/'.$transaction->id+1,
            [
                "Authorization"=>'ambatublow'
            ])->assertStatus(404)
            ->assertJson([
                'error'=>[
                    'message'=>[
                        "Transaction not found"
                    ]
                ]
            ]);
    }

    public function testDetailTransactionOtherUser()
    {

        $this->seed([UserSeeder::class,TransactionSeeder::class]);
        $transaction=Transaction::query()->limit(1)->first();
        $this->get('/api/transaction/history/'.$transaction->id,
            [
                "Authorization"=>'ambatublow2'
            ])->assertStatus(404)
            ->assertJson([
                'error'=>[
                    'message'=>[
                        "Transaction not found"
                    ]
                ]
            ]);
    }

    public function testUpdateTransactionSuccess()
    {
        $this->seed([UserSeeder::class,TransactionSeeder::class]);
        $transaction=Transaction::query()->limit(1)->first();
        $this->patch('/api/transaction/edit/'.$transaction->id,
            [
            'tanggalTransaksi'=>'2022-01-01',
            'jenisTransaksi'=>'pengeluaran',
            'nominal'=>'2000000',
            'deskripsi'=>'Pengeluaran Harian'
            ],
            [
                "Authorization"=>'ambatublow'
            ]
        )->assertStatus(200)
            ->assertJson([
            "data"=>[
                'tanggalTransaksi'=>'2022-01-01',
                'jenisTransaksi'=>'pengeluaran',
                'nominal'=>'2000000',
                'deskripsi'=>'Pengeluaran Harian'
            ]
            ]);

    }
    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class,TransactionSeeder::class]);
        $transaction=Transaction::query()->limit(1)->first();
        $this->patch('/api/transaction/edit/'.$transaction->id,
            [
                'tanggalTransaksi'=>'',
                'jenisTransaksi'=>'pengeluaran',
                'nominal'=>'2000000',
                'deskripsi'=>'Pengeluaran Harian'
            ],
            [
                "Authorization"=>'ambatublow'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors'=>[
                    'tanggalTransaksi'=>[
                        "The tanggal transaksi field is required."
                    ]
                ]
            ]);
    }

    public function testDeleteTransactionSuccess()
    {
        $this->seed([UserSeeder::class,TransactionSeeder::class]);
        $transaction=Transaction::query()->limit(1)->first();
        $this->delete('/api/transaction/delete/'.$transaction->id, [],
            [
                "Authorization"=>'ambatublow'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data'=>true
            ]);
    }
    public function testDeleteTransactionNotFound()
    {
        $this->seed([UserSeeder::class,TransactionSeeder::class]);
        $transaction=Transaction::query()->limit(1)->first();
        $this->delete('/api/transaction/delete/'.$transaction->id+1, [],
            [
                "Authorization"=>'ambatublow'
            ]
        )->assertStatus(404)
            ->assertJson([
                'error'=>[
                    'message'=>[
                        "Transaction not found"
                    ]
                ]
            ]);

    }

    public function testGetByDateSuccess()
    {
        $this->seed([UserSeeder::class,TransactionSeeder::class]);
        $data = $this->get('/api/transaction/history/date_range?start_date=2022-01-01&end_date=2023-01-01', [
            "Authorization" => 'ambatublow'
        ])->assertStatus(200)->assertJson([
            "data"=>[
                [
                    'id'=>'2',
                    'tanggalTransaksi'=>'2022-02-01',
                    'jenisTransaksi'=>'pengeluaran',
                    'nominal'=>'1200000',
                    'deskripsi'=>'Pengeluaran dari pemilik',
                ],
                [
                    'id'=>'1',
                    'tanggalTransaksi'=>'2022-01-01',
                    'jenisTransaksi'=>'pemasukan',
                    'nominal'=>'1000000',
                    'deskripsi'=>'Pemasukan dari pemilik',
                ]
            ]
        ]);
    }
}
