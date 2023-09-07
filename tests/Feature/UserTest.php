<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'fangki',
            'password' => 'rahasia',
            'name' => 'Fangki Igo Pramana'
        ])
            ->assertStatus(201)
            ->assertJson([
                "data" => [
                    'username' => 'fangki',
                    'name' => 'Fangki Igo Pramana'
                ]
            ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        "The username field is required."
                    ],
                    'password' => [
                        "The password field is required."
                    ],
                    'name' => [
                        "The name field is required."
                    ],
                ]
            ]);
    }

    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'fangki',
            'password' => 'rahasia',
            'name' => 'Fangki Igo Pramana'
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        "username already registered"
                    ]
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test',
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'username' => 'test',
                'name' => 'test',
            ]
        ]);

        $user = User::where('username','test')->first();
        self::assertNotNull($user->token);

    }

    public function testLoginFailedUsernameNotFound(){
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test',
        ])->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => [
                    'username or password wrong'
                ]
            ]
            ]);
    }
    
    public function testLoginFailedPasswordWrong(){
        $this->seed(UserSeeder::class);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'salah',
        ])->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => [
                    'username or password wrong'
                ]
            ]
            ]);
    }
}