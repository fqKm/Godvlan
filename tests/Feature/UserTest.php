<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegSucces()
    {
        $this->post('/api/register',[
            'email'=>'hitamlegamberbulu@gmail.com',
            'password'=>'Biggas123',
            'name'=>'Rusdi',
            'company'=>'Godvlan'
        ])->assertStatus(201)
            ->assertJson([
                "data"=> [
                    'email'=>'hitamlegamberbulu@gmail.com',
                    'name'=>'Rusdi',
                    'company'=>'Godvlan'
                ]
            ]);
    }

    public function testRegFailed()
    {
        $this->post('/api/register',[
            'email'=>'',
            'password'=>'',
            'name'=>'',
            'company'=>''
        ])->assertStatus(400)
            ->assertJson([
                "errors"=> [
                    'email'=>[
                        "The email field is required."
                    ],
                    'password'=>[
                        "The password field is required."
                    ],
                    'name'=>[
                        "The name field is required."
                    ]
                ]
            ]);
    }

    public function testRegEmailExist()
    {
        $this->testRegSucces();
        $this->post('/api/register',[
            'email'=>'hitamlegamberbulu@gmail.com',
            'password'=>'Biggas123',
            'name'=>'Rusdi',
            'company'=>'Godvlan'
        ])->assertStatus(400)
            ->assertJson([
                "errors"=> [
                    "message"=>[
                        "Email sudah terdaftar"
                    ]
                ]
            ]);
    }
    public function testLoginSucces()
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/login',[
            'email'=>'Negroid',
            'password'=>'masamba123'
        ])->assertStatus(200)
            ->assertJson([
                "data"=> [
                    'email'=>'Negroid',
                    'name'=>'Rusdi',
                    'company'=>'Godvlan'
                ]
            ]);
        $user = User::where('email','Negroid')->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedPasswordisWrong()
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/login',[
            'email'=>'Negroid',
            'password'=>'masamba'
        ])->assertStatus(401)
            ->assertJson([
                "errors"=> [
                    "message"=>[
                        "Email or Password is wrong"
                    ]
                ]
            ]);
    }
    public function testLoginFailedUsernamenotFound()
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/login',[
            'email'=>'Negro',
            'password'=>'masamba123'
        ])->assertStatus(401)
            ->assertJson([
                "errors"=> [
                    "message"=>[
                        "Email or Password is wrong"
                    ]
                ]
            ]);
    }
}
