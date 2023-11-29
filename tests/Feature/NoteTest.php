<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function a_user_can_be_registered()
    {
        $this->withoutExceptionHandling();

        $data = [
            'name' => 'Muchaha',
            'email' => 'mexico@mail.mx',
            'password' => 'Korablik123'
        ];

        $res = $this->post('/api/register', $data);
        $res->assertOk();
    }
}
