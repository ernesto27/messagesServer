<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_should_respond_a_valid_json_of_users()
    {
        $users = factory(\App\User::class, 4)->create();
        $response = $this->get('api/users');
        $response
            ->assertStatus(200)
            ->assertJson([[
                'id' => true,
                'name' => true,
                'email' => true,
                'created_at' => true,
                'updated_at' => true
            ]]);
    }


    /** @test */
    function it_should_register_a_user()
    {
        $user = factory(\App\User::class)->make();
        $userArray = $user->toArray();
        $userArray['password'] = 'somepassword';

        $response = $this->post('api/users', $userArray);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'ok'
            ]);

    }

    /** @test */
    function it_should_respond_status_422_if_request_is_not_valid_register_user()
    {
        $response = $this->post('api/users', []);
        $response
            ->assertStatus(422)
            ->assertJson([
                'status' => true,
                'errors' => true
            ]);
    }

    









}
