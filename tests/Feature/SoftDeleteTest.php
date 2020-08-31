<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;
use Tests\Fixtures\Models\ZcwiltUser;

class SoftDeleteTest extends DatabaseTestCase
{
    /** @test */
    public function gets_items_with_trashed_items()
    {
        $model = new ZcwiltUser();
        $testResult = $model->get();
        $this->delete("/user/1");
        $response = $this->get("/user?paginate=no&withTrashed=");
        $data = $response->getData()->data;
        $this->assertTrue(count($testResult->toArray()) === count($data));
    }

    /** @test */
    public function gets_only_trashed_items()
    {
        $this->delete("/user/1");
        $response = $this->get("/user?paginate=no&onlyTrashed=");
        $data = $response->getData()->data;
        $this->assertTrue(1 === count($data));
    }

    /** @test */
    public function gets_show_only_trashed_when_not_supported()
    {
        $response = $this->get("/dummy?paginate=no&onlyTrashed=");
        $response = json_decode($response->getContent());
        $this->assertTrue($response->error->message === 'Model does not support soft deletes');
    }

    /** @test */
    public function gets_show_with_trashed_when_not_supported()
    {
        $response = $this->get("/dummy?paginate=no&withTrashed=");
        $response = json_decode($response->getContent());
        $this->assertTrue($response->error->message === 'Model does not support soft deletes');
    }

    /** @test */
    public function restores_a_single_entity()
    {
        $this->delete("/user/1");
        $response = $this->get("/user/1?withTrashed&restore");
        $data = $response->getData()->data;
        $this->assertEquals(1, $data[0]->id);
        $response = $this->get("/user/1");
        $data = $response->getData()->data;
        $this->assertEquals($data[0]->id, 1);
    }
}
