<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\PassportTestCase;


class PassportTest extends PassportTestCase
{
    use DatabaseTransactions;

    protected array $scopes = ['restricted-scope'];

    /** @test */
    public function testRestrictedRoute()
    {
        $this->get('/api/login')
            ->assertResponseStatus(401);
    }

    /** @test */
    public function testUnrestrictedRoute()
    {
        $this->get('/api/register')
            ->assertResponseOk();
    }
}
