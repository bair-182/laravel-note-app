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
    /**
     * @OA\Post(
     *        path="/api/notes/",
     *        summary="Создание заметки",
     *        tags={"Note"},
     *        security={{ "bearerAuth": {} }},
     *
     *        @OA\RequestBody(
     *            @OA\JsonContent(
     *                allOf={
     *                    @OA\Schema (
     *                        @OA\Property (property="title", type="string", example="Work"),
     *                        @OA\Property (property="fields", type="array", @OA\Items(
     *                            @OA\Property (property="title", type="string", example="Worker"),
     *                            @OA\Property (property="type", type="enum('string', 'integer', 'float', 'boolean')", example="string"),
     *                            @OA\Property (property="value", type="string", example="Ivan Ivanov"),
     *                        )),
     *                    ),
     *                }
     *            ),
     *        ),
     *        @OA\Response(
     *            response=200,
     *            description="OK",
     *            @OA\JsonContent(
     *                @OA\Property (property="data", type="object",
     *                    @OA\Property (property="title", type="string", example="Some title"),
     *                    @OA\Property (property="user_id", type="integer", example=1),
     *                    @OA\Property (property="id", type="integer", example=1),
     *                ),
     *                @OA\Property (property="message", type="string", example="New note created."),
     *            ),
     *        ),
     *   )
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


    /**
     * @OA\Get(
     *        path="/api/notes/",
     *        summary="Получение заметок",
     *        tags={"Note"},
     *        security={{ "bearerAuth": {} }},
     *
     *        @OA\Response(
     *            response=200,
     *            description="OK",
     *            @OA\JsonContent(
     *                @OA\Property (property="data", type="object",
     *                    @OA\Property (property="id", type="integer", example=1),
     *                    @OA\Property (property="created_at", type="timestamp", example="2023-11-28 17:50:37"),
     *                    @OA\Property (property="updated_at", type="timestamp", example=null),
     *                    @OA\Property (property="title", type="string", example="Some title"),
     *                    @OA\Property (property="user_id", type="integer", example=1),
     *                    @OA\Property (property="fields", type="array", @OA\Items(
     *                              @OA\Property (property="id", type="integer", example=1),
     *                              @OA\Property (property="created_at", type="timestamp", example="2023-11-28 17:50:37"),
     *                              @OA\Property (property="updated_at", type="timestamp", example=null),
     *                              @OA\Property (property="title", type="string", example="Worker"),
     *                              @OA\Property (property="type", type="enum('string', 'integer', 'float', 'boolean')", example="string"),
     *                              @OA\Property (property="value", type="string", example="Ivan Ivanov"),
     *                          )),
     *                ),
     *            ),
     *        ),
     *   )
     */
    public function index(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Not authorized'], 401);
        }

        $user = Auth::user();
        if ($user->role === User::ROLE_ADMIN) {
            $data = NoteResource::collection(Note::with(
                'fields.fieldString',
                'fields.fieldInt',
                'fields.fieldFloat',
                'fields.fieldBool')
                ->orderBy('id', 'DESC')
                ->get()
            );
        } else {
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


    /**
     * @OA\Put(
     *         path="/api/notes/{id}",
     *         summary="Редактирование заметки",
     *         tags={"Note"},
     *         security={{ "bearerAuth": {} }},
     *         @OA\Parameter (
     *             description="ID заметки",
     *             in="path",
     *             name="id",
     *             required=true,
     *             example=1
     *         ),
     *
     *        @OA\RequestBody(
     *             @OA\JsonContent(
     *                 allOf={
     *                     @OA\Schema (
     *                         @OA\Property (property="title", type="string", example="Work"),
     *                         @OA\Property (property="fields", type="array", @OA\Items(
     *                             @OA\Property (property="id", type="integer", example=1),
     *                             @OA\Property (property="title", type="string", example="Worker"),
     *                             @OA\Property (property="type", type="string", example="string"),
     *                             @OA\Property (property="value", type="string", example="Ivan Ivanov"),
     *                         )),
     *                     ),
     *                 }
     *             ),
     *         ),
     *
     *         @OA\Response(
     *             response=200,
     *             description="OK",
     *             @OA\JsonContent(
     *                 @OA\Property (property="message", type="string", example="Note is updated."),
     *             ),
     *         ),
     *    )
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


    /**
     * @OA\Delete(
     *          path="/api/notes/{id}",
     *          summary="Удаление заметки",
     *          tags={"Note"},
     *          security={{ "bearerAuth": {} }},
     *          @OA\Parameter (
     *              description="ID заметки",
     *              in="path",
     *              name="id",
     *              required=true,
     *              example=1
     *          ),
     *
     *          @OA\Response(
     *              response=200,
     *              description="OK",
     *              @OA\JsonContent(
     *                  @OA\Property (property="message", type="string", example="Note is deleted."),
     *              ),
     *          ),
     *     )
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
        return response()->json(['message' => 'Note is deleted.'], 200);
    }

}
