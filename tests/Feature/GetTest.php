<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;
use Tests\Fixtures\Models\ZcwiltUser;

class GetTest extends DatabaseTestCase
{

    /** @test */
    public function gets_a_single_item()
    {
        $response = $this->get("/user/1");
        $response->assertStatus(200);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data->id === 1);
    }

    /** @test */
    public function gets_an_invalid_item()
    {
        $response = $this->get("/user/1001");
        $response->assertStatus(400);
        $message = json_decode($response->getContent())->error->message;
        $this->assertTrue($message === 'Item does not exist');
    }

    /** @test */
    public function gets_paginated_items()
    {
        $response = $this->get("/user");
        $response->assertStatus(200);
        $data = $response->getData()->data;
        $this->assertTrue(count($data) === 15);
    }

    /** @test */
    public function gets_items_using_query_parameters()
    {
        $user = new ZcwiltUser();
        $modelResult = $user->whereBetween('id', [1,20])->whereBetween('age', [20,40])->count();
        $response = $this->get("/user?whereBetween[]=id:1:20&whereBetween[]=age:20:40");
        $response->assertStatus(200);
        $responseResult = count($response->getData()->data);
        $this->assertTrue($modelResult === $responseResult);
    }
}
