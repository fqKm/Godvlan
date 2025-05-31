<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotEquals;

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

    public function testGetProfileSucces()
    {
        $this->seed(UserSeeder::class);
        $this->get('/api/profile',[
            'Authorization'=>'ambatublow'
        ])->assertStatus(200)
            ->assertJson([
                'data'=>[
                    'email'=>'Negroid',
                    'name'=>'Rusdi',
                    'company'=>'Godvlan',
                ]
            ]);
    }

    public function testGetProfileUnauthorized()
    {
        $this->seed(UserSeeder::class);
        $this->get('/api/profile')
            ->assertStatus(401)
            ->assertJson([
                'error'=>[
                    'message'=>[
                        "Unauthorized"
                    ]
                ]
            ]);
    }

    public function testUpdateInvalidToken()
    {
        $this->seed(UserSeeder::class);
        $this->get('/api/profile',[
            'Authorization'=>'Ambatukam'
        ])->assertStatus(401)
            ->assertJson([
                'error'=>[
                    'message'=>[
                        "Unauthorized"
                    ]
                ]
            ]);
    }

    public function testUpdatePasswordSucces(){
        $this->seed(UserSeeder::class);
        $oldUser=User::where('email','Negroid')->first();
        $this->patch('/api/profile/update',
            [
                'password'=>'ambasings123'
            ],
            [
            'Authorization'=>'ambatublow'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data'=>[
                    'email'=>'Negroid',
                    'name'=>'Rusdi',
                    'company'=>'Godvlan',
                ]
            ]);
        $updatedUser=User::where('email','Negroid')->first();
        self::assertNotEquals($oldUser->password,$updatedUser->password);
    }

    public function testUpdateNameSucces(){
        $this->seed(UserSeeder::class);
        $oldUser=User::where('email','Negroid')->first();
        $this->patch('/api/profile/update',
            [
                'name'=>'Azril'
            ],
            [
                'Authorization'=>'ambatublow'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data'=>[
                    'email'=>'Negroid',
                    'name'=>'Azril',
                    'company'=>'Godvlan',
                ]
            ]);
        $updatedUser=User::where('email','Negroid')->first();
        self::assertNotEquals($oldUser->name,$updatedUser->name);
    }

    public function testUpdateCompanySucces(){
        $this->seed(UserSeeder::class);
        $oldUser=User::where('email','Negroid')->first();
        $this->patch('/api/profile/update',
            [
                'company'=>'Azril'
            ],
            [
                'Authorization'=>'ambatublow'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data'=>[
                    'email'=>'Negroid',
                    'name'=>'Rusdi',
                    'company'=>'Azril',
                ]
            ]);
        $updatedUser=User::where('email','Negroid')->first();
        self::assertNotEquals($oldUser->company,$updatedUser->company);
    }
    public function testUpdateFailed(){
        $this->seed(UserSeeder::class);
        $oldUser=User::where('email','Negroid')->first();
        $this->patch('/api/profile/update',
            [
                'name'=>'Raja Diraja Senja di Atas Awan, Pelindung Hutan Rimba yang Hilang, Penjaga Rahasia Sungai yang Mengalir ke Samudra Abadi, Pewaris Cahaya Bintang Paling Tua'
            ],
            [
                'Authorization'=>'ambatublow'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors'=>[
                    'name'=>[
                        "The name field must not be greater than 100 characters."
                    ]
                ]
            ]);
    }

    public function testLogoutSucces(){
        $this->seed(UserSeeder::class);
        $this->delete('/api/logout',[],[
            'Authorization'=>'ambatublow'
        ])->assertStatus(200)
            ->assertJson([
                'data'=>true
            ]);
    }
    public function testLogoutFailed(){
        $this->seed(UserSeeder::class);
        $this->delete('/api/logout',[
            'Authorization'=>'Ambatukam'
        ])->assertStatus(401)
            ->assertJson([
                'error'=>[
                    'message'=>[
                        "Unauthorized"
                    ]
                ]
            ]);
    }

}
