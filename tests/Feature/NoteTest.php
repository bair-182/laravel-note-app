<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
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

    /** @test */
    public function it_should_return_402_when_data_is_invalid ()
    {
        $this->assertJson(402);

        $response = $this->post('/api/login', [
            'email' => 'example@example.com',
            'password' => 'wrong',
        ]);

        $this->assertEquals(402, $response->statusCode);
    }


}
