<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Message;

class MessagesTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    function it_should_respond_a_valid_json_of_messages()
    {
        $messages = factory(Message::class, 3)->create();

        $response = $this->json('GET', '/api/messages');
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
        $response = $this->json('POST', '/api/messages', $message->toArray());
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'ok'
            ]);
    }

    /** @test */
    function it_should_respond_error_if_request_is_not_valid_when_save_message()
    {
        $response = $this->post('/api/messages', array());
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
        $response = $this->patch('/api/messages/' . $message->id, $post);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'ok'
            ]);

        $this->assertDatabaseHas('messages', ['body' => $text ]);
    }

    /** @test */
    function it_should_respond_error_if_request_is_not_valid_when_update_message()
    {
        $message = factory(Message::class)->create();
        $response = $this->patch('/api/messages/' . $message->id, array());
        $response
            ->assertStatus(422)
            ->assertJson([
                'status' => true,
                'errors' => true
            ]);
    }

    /** @test */
    function it_should_show_error_if_id_message_doesnt_exists_on_update()
    {
        $text = 'message edited from test';
        $post = ['body' => $text];
        $response = $this->patch('/api/messages/9999999999999999999999', $post);
        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => true,
                'message' => true
            ]);
    }
}
