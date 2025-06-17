<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email'=>'Negroid@gmail.com',
            'password'=>Hash::make('masamba123'),
            'name'=>'Rusdi',
            'company'=>'Godvlan',
            'token'=>'ambatublow'
        ]);
        User::create([
            'email'=>'Negroid2@gmail.com',
            'password'=>Hash::make('masamba1232'),
            'name'=>'Rusdi2',
            'company'=>'Godvlan2',
            'token'=>'ambatublow2'
        ]);
    }
}
