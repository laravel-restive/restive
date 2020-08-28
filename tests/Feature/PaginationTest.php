<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;

class PaginationTest extends DatabaseTestCase
{

    /** @test */
    public function sets_pagination_per_page()
    {
        $response = $this->get("/user?per_page=5");
        $response->assertStatus(200);
        $data = $response->getData()->data;
        $this->assertTrue(count($data) === 5);
    }

    /** @test */
    public function sets_no_pagination_per_page()
    {
        $response = $this->get("/user?paginate=no");
        $response->assertStatus(200);
        $data = $response->getData()->data;
        $this->assertTrue(count($data) > 15);
    }
}
