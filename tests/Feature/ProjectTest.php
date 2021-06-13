<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function testAuthorized()
    {

        $response = $this->post('/api/auth/login', [
            'login' => 'thegrislyone',
            'password' => '123456'
        ]);

        $response->assertJsonPath('status', true);

    }

    public function testItemsList()
    {

        $response = $this->get('/api/items/get-items?page=1');

        $response->assertOk();

    }
}
