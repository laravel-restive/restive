<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Request;
use Tests\DatabaseTestCase;

class PutTest extends DatabaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function updates_a_current_item()
    {
        $newID = $this->createEntry();
        $response = $this->put("/user/" . $newID, [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently',
            'age' => 45
        ]);
        $response->assertStatus(200);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data === 'affected rows = 1');
    }

    /** @test */
    public function updates_a_nonexistent_item()
    {
        $response = $this->put("/user/1001", [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently',
            'age' => 45
        ]);
        $response->assertStatus(200);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data === 'affected rows = 0');
    }

    /** @test */
    public function updates_with_a_existing_email()
    {
        $newID = $this->createEntry();
        $response = $this->put("/user/1", [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently',
            'age' => 38
        ]);
        $response = json_decode($response->getContent());
        $message = $response->error->message->email;
        $this->assertContains('The email has already been taken.', $message);
    }

    /** @test */
    public function updates_with_a_missing_email()
    {
        $newID = $this->createEntry();
        $response = $this->put("/user/1", [
            'name' => 'Dirk Gently',
            'age' => 38
        ]);
        $response = json_decode($response->getContent());
        $message = $response->error->message->email[0];
        $this->assertStringContainsString('The email field is required.', $message);
    }

    /** @test */
    public function updates_using_a_where_clause()
    {
        $newID = $this->createEntry();
        $response = $this->put("/user?where[]=email:eq:dirk@holisticdetective.com", [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently',
            'age' => 45,
        ]);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data === 'affected rows = 1');
    }

    protected function createEntry()
    {
        $response = $this->post("/user", [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently',
            'age' => 38,
        ]);
        $newID = json_decode($response->getContent())->data->id;
        return $newID;
    }
}
