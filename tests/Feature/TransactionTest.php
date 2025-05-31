<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
