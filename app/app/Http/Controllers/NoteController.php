<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Jobs\SendEmailJob;
use App\Jobs\SendJob;
use App\Models\Note;
use App\Services\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;


class NoteController extends Controller
{


    /** Просмотр заметок.
     *
     * Каждый пользователь имеет доступ только к своим заметкам. Администратор – ко всем
     *
     * @return JsonResponse|NoteResource|Note|AnonymousResourceCollection
     */
    public function index(): JsonResponse|NoteResource|Note|AnonymousResourceCollection
    {
        $user = Auth::user();
        if ($this->isAuthorized() && $this->isAdmin()) {
            $data = NoteResource::collection(Note::with(
                'fields.fieldString',
                'fields.fieldInt',
                'fields.fieldFloat',
                'fields.fieldBool')
                ->get()
            );
        } elseif ($this->isAuthorized() && !$this->isAdmin()) {
            $data = NoteResource::collection(Note::where('user_id', $user->id)
                ->with(
                    'fields.fieldString',
                    'fields.fieldInt',
                    'fields.fieldFloat',
                    'fields.fieldBool')
                ->get()
            );
        } else {
            $data = response()->json('Unauthorized user', 401);
        }
        return $data;
    }


    /** Создание заметки.
     *
     * При создании заметки пользователем создается событие, которое
     * асинхронно отправляет уведомление администратору по почте
     *
     * @param NoteRequest $request
     * @return JsonResponse
     */
    public function store(NoteRequest $request): JsonResponse
    {
        $noteService = new NoteService();
        $newNote = $noteService->create($request->all());

        if (isset($newNote)) {
            SendEmailJob::dispatch()->onQueue('default');
            return response()->json(['data' => $newNote, 'message' => 'New note created.'], 200);
        }
        return response()->json(['error' => 'Failed to create a note.'], 500);
    }


    /** Обновление заметки
     *
     * @param NoteRequest $request
     * @return JsonResponse
     */
    public function update(NoteRequest $request): JsonResponse
    {
        $noteService = new NoteService();
        $updatedNote = $noteService->update($request->all());

        if (isset($updatedNote)) {
            return response()->json(['message' => 'Note is updated.'], 201);
        }
        return response()->json(['error' => 'Failed to update a note.'], 500);
    }


    /** Удаление заметки.
     *
     * @param int $noteId
     * @return JsonResponse
     */
    public function delete(int $noteId): JsonResponse
    {
        $noteService = new NoteService();
        $isDeletedNote = $noteService->delete($noteId);

        if ($isDeletedNote === true) {
            return response()->json(['message' => 'Note is deleted.'], 201);
        } else {
            return $isDeletedNote;
        }
    }


    /** Проверка на права администратора.
     *
     * @return bool
     */
    private function isAdmin(): bool
    {
        return Auth::user()->role === 'admin';
    }


    /** Проверка авторизации.
     *
     * Реализовано с Laravel Passport
     *
     * @return bool
     */
    private function isAuthorized(): bool
    {
        return Auth::check();
    }
}
