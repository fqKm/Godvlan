<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user=User::where('email','Negroid@gmail.com')->first();
        Transaction::create([
            'tanggalTransaksi'=>'2022-01-01',
            'jenisTransaksi'=>'pemasukan',
            'nominal'=>'1000000',
            'deskripsi'=>'Pemasukan dari pemilik',
            'user_id'=>$user->id
        ]);
    }
}
