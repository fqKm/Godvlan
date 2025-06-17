<?php

namespace Tests\Feature;

use Database\Seeders\TransactionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatBotTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_ask(): void
    {
        $this->seed([UserSeeder::class, TransactionSeeder::class]);
        $response = $this->post('api/chatbot/ask', [
            'prompt' => 'Berapa total uang saya?',
        ], ["Authorization"=>'ambatublow'])->assertStatus(200);
        dump($response);
    }
}
