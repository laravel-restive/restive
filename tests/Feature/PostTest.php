<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;

class PostTest extends DatabaseTestCase
{
    /** @test */
    public function post_user_fails_missing_age_parameter()
    {
        $response = $this->post("/user", [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently'
        ]);
        $response->assertStatus(400);
    }

    /** @test */
    public function post_user_fails_missing_email_parameter()
    {
        $response = $this->post("/user", []);
        $response->assertStatus(400);
        $response = json_decode($response->getContent());
        $message = $response->error->message->email[0];
        $this->assertContains('The email field is required.', $message);
    }

    /** @test */
    public function post_user_with_correct_parameters()
    {
        $response = $this->post("/user", [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently',
            'age' => 38
        ]);
        $response->assertStatus(200);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data->age === 38);
    }
}
