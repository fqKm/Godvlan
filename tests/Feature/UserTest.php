<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegSucces()
    {
        $this->post('/api/user',[
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

    }

    public function testRegEmailExist()
    {

    }
}
