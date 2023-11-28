<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\NoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    ///** @test */
    public function it_can_create_a_note()
    {
        $this->withoutExceptionHandling();

//        Passport::actingAs(
//            factory(User::class)->create(),
//            ['create-servers']
//        );

        $noteTitle = 'Название заметки';
//
//        $noteService = new NoteService();
//        $newNote = $noteService->create((array)$noteTitle);

        $createdNote = $this->post('/api/notes', [
            'title' => $noteTitle,
        ],['Accept' => 'application/json']);

        var_dump($createdNote);
        $this->assertEquals($createdNote->title, $noteTitle);
    }

}
