<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;
use Tests\Fixtures\Models\ZcwiltUser;

class DeleteTest extends DatabaseTestCase
{
    /** @test */
    public function deletes_a_single_resource()
    {
        $response = $this->delete("/user/1");
        $response->assertStatus(200);
        $data = $response->getData()->data;
        $this->assertEquals($data[0]->id, 1);
    }


    /** @test */
    public function deletes_a_resource_using_where()
    {
        $user = new ZcwiltUser();
        $countBefore = $user->all()->count();
        $response = $this->delete("/user?where[]=id:eq:2");
        $countAfter = $user->all()->count();
        $data = $response->getData()->data;
        $this->assertEquals($data[0]->id, 2);
        $this->assertEquals($countAfter, $countBefore-1);
    }

    /** @test */
    public function deletes_multiple_entries()
    {
        $user = new ZcwiltUser();
        $countBefore = $user->all()->count();
        $response = $this->delete("/user?whereBetween=age:13:19");
        $countAfter = $user->all()->count();
        $data = $response->getData()->data;
        $deletedCount = count($data);
        $trashedCount = $user->onlyTrashed()->count();
        $this->assertEquals($countAfter, $countBefore-$deletedCount);
        $this->assertEquals($deletedCount, $trashedCount);

    }

    /** @test */
    public function deletes_a_nonexistent_resource_using_where()
    {
        $response = $this->delete("/user?where=id:eq:1001");
        $data = $response->getData()->data;
        $this->assertTrue(count($data) === 0);
    }

    /** @test */
    public function deletes_a_nonexistent_resource_using_id()
    {
        $response = $this->delete("/user/1001");
        $response->assertStatus(200);
        $data = $response->getData()->data;
        $this->assertTrue(count($data) === 0);
    }

    /** @test */
    public function deletes_a__resource_using_invalid_parser()
    {
        $response = $this->delete("/user?foo=id:eq:1001");
        $response->assertStatus(400);
    }

    /** @test */
    public function force_deletes_a_single_item()
    {
        $user = new ZcwiltUser();
        $countBefore = $user->all()->count();
        $response = $this->delete("/user/1?force=true");
        $countAfter = $user->withTrashed()->count();
        $response->assertStatus(200);
        $this->assertTrue($countBefore != $countAfter);
    }
}
