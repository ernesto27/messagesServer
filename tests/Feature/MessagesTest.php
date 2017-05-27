<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Message;
use \App\User;

class MessagesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_should_respond_a_valid_json_of_messages()
    {
        $messages = factory(Message::class, 3)->create();
        $response = $this->json('GET', '/api/messages', ['api_token' => $messages[0]->user->api_token]);
        $response
            ->assertStatus(200)
            ->assertJson([[
                'id' => true,
                'body' => true,
                'created_at' => true,
                'updated_at' => true
            ]]);
    }

    /** @test */
    function it_should_save_a_new_message()
    {
        $message = factory(Message::class)->create();
        $response = $this->json('POST', '/api/messages?api_token=' . $message->user->api_token  , $message->toArray());
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'ok'
            ]);
    }

    /** @test */
    function it_should_respond_error_if_request_is_not_valid_when_save_message()
    {
        $user = factory(User::class)->create();
        $response = $this->post('/api/messages?api_token=' . $user->api_token, array());
        $response
            ->assertStatus(422)
            ->assertJson([
                'status' => true,
                'errors' => true
            ]);
    }

    /** @test */
    function it_should_respond_success_when_edit_a_message()
    {
        $message = factory(Message::class)->create();
        $text = 'message edited from test';
        $post = ['body' => $text];
        $response = $this->patch('/api/messages/' . $message->id . '?api_token=' . $message->user->api_token , $post);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'ok'
            ]);

        $this->assertDatabaseHas('messages', ['body' => $text ]);
    }

    /** @test */
    function it_should_respond_error_if_request_is_not_valid_on_update_message()
    {
        $message = factory(Message::class)->create();
        $response = $this->patch('/api/messages/' . $message->id . '?api_token=' . $message->user->api_token , array());
        $response
            ->assertStatus(422)
            ->assertJson([
                'status' => true,
                'errors' => true
            ]);
    }

    /** @test */
    function it_should_respond_status_404_if_invalid_id_message_doesnt_exists_on_update()
    {
        $text = 'message edited from test';
        $post = ['body' => $text];
        $user = factory(User::class)->create();
        $response = $this->patch('/api/messages/9999999999999999999999?api_token=' . $user->api_token , $post);
        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => true,
                'message' => true
            ]);
    }


    /** @test */
    function it_should_respond_status_200_on_delete_message()
    {
        $message = factory(Message::class)->create();
        $response = $this->delete('/api/messages/' . $message->id . '?api_token=' . $message->user->api_token , array());
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);
    }

    /** @test */
    function it_should_respond_status_404_if_id_message_doesnt_exists_on_delete_message()
    {
        $user = factory(User::class)->create();
        $response = $this->delete('/api/messages/9999999999999999999999?api_token=' . $user->api_token);
        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => true,
                'message' => true
            ]);
    }



}
