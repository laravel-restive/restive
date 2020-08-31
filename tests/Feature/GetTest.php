<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;

class GetTest extends DatabaseTestCase
{

    /** @test */
    public function gets_a_single_item()
    {
        $response = $this->get("/user/1");
        $response->assertStatus(200);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data[0]->id === 1);
    }

    /** @test */
    public function gets_an_invalid_item()
    {
        $response = $this->get("/user/1001");
        $response->assertStatus(200);
        $data = $response->getData()->data;
        $this->assertTrue(count($data) === 0);
    }

    /** @test */
    public function gets_paginated_items()
    {
        $response = $this->get("/user");
        $response->assertStatus(200);
        $data = $response->getData()->data;
        $this->assertTrue(count($data) === 15);
    }
}
