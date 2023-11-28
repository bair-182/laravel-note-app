<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Jobs\SendEmailJob;
use App\Models\Note;
use App\Models\User;
use App\Services\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class NoteController extends Controller
{

    /** Просмотр заметок.
     *
     * Каждый пользователь имеет доступ только к своим заметкам. Администратор – ко всем
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Not authorized'], 401);
        }

        $user = Auth::user();
        if (Auth::user()->role === User::ROLE_ADMIN) {
            $data = NoteResource::collection(Note::with(
                'fields.fieldString',
                'fields.fieldInt',
                'fields.fieldFloat',
                'fields.fieldBool')
                ->orderBy('id', 'DESC')
                ->get()
            );
        } elseif (Auth::user()->role === User::ROLE_USER) {
            $data = NoteResource::collection(Note::where('user_id', $user->id)
                ->with(
                    'fields.fieldString',
                    'fields.fieldInt',
                    'fields.fieldFloat',
                    'fields.fieldBool')
                ->orderBy('id', 'DESC')
                ->get()

            );
        }
        return response()->json(['data' => $data], 200);
    }


    /** Создание заметки.
     *
     * При создании заметки пользователем создается событие, которое
     * асинхронно отправляет уведомление администратору по почте
     *
     * @param NoteRequest $request
     * @param NoteService $noteService
     * @return JsonResponse
     */
    public function store(NoteRequest $request, NoteService $noteService): JsonResponse
    {
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
     * @param int $noteId
     * @param NoteService $noteService
     * @return JsonResponse
     */
    public function update(NoteRequest $request, int $noteId, NoteService $noteService): JsonResponse
    {
        $userId = Auth::user()->id;
        $note = Note::where('id', $noteId)->first();

        if ($userId !== $note->user_id) {
            return response()->json(['error' => 'Not enough rights to edit this note'], 403);
        }

        $updatedNote = $noteService->update($note, $request);

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
        $note = Note::find($noteId);
        if (!$note) {
            return response()->json(['error' => 'No notes with this ID were found.'], 404);
        }
        if ($note->user_id !== Auth::user()->id) {
            return response()->json(['error' => 'There are not enough rights to delete this note.'], 403);
        }
        $note->delete();
        return response()->json(['message' => 'Note is deleted.'], 201);
    }

}
