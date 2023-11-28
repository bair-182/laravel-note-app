<?php

namespace App\Services;

use App\Http\Requests\NoteRequest;
use App\Models\Field;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class NoteService
{

    /** Создание Заметки.
     *
     * @param array $data принимает массив с полями для создания заметки.
     * @return Note|null
     */
    public function create(array $data): ?Note
    {
        DB::beginTransaction();
        try {
            $note = Note::create([
                'title' => $data['title'],
                'user_id' => Auth::user()->id
            ]);

            if (isset($data['fields'])) {
                foreach ($data['fields'] as $field) {
                    $newField = Field::create([
                        'title' => $field['title'],
                        'type' => $field['type'],
                        'note_id' => $note->id
                    ]);

                    switch ($newField['type']) {
                        case Field::TYPE_STRING:
                            $newField->fieldString()->create(['value' => $field['value']]);
                            break;
                        case Field::TYPE_INTEGER:
                            $newField->fieldInt()->create(['value' => $field['value']]);
                            break;
                        case Field::TYPE_FLOAT:
                            $newField->fieldFloat()->create(['value' => $field['value']]);
                            break;
                        case Field::TYPE_BOOLEAN:
                            $newField->fieldBool()->create(['value' => $field['value']]);
                            break;
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating a note', [
                'error-message' => $e->getMessage(),
                'note-data' => $data,
            ]);
            return null;
        }
        return $note;
    }

    /** Обновление заметки.
     *
     * @param Note $note объект обновляемой заметки из базы данных.
     * @param NoteRequest $data объект с новыми данными
     * @return Note|null
     */
    public function update(Note $note, NoteRequest $data): ?Note
    {
        DB::beginTransaction();
        try {
            $note->update([
                'title' => $data['title'],
            ]);

            if (isset($data['fields'])) {
                foreach ($data['fields'] as $field) {
                    $existingField = Field::where('id', $field['id'])->first();
                    $existingField->update(['title' => $field['title']]);

                    switch ($existingField['type']) {
                        case Field::TYPE_STRING:
                            $existingField->fieldString()->update(
                                ['value' => $field['value']]);
                            break;
                        case Field::TYPE_INTEGER:
                            $existingField->fieldInt()->update(
                                ['value' => $field['value']]);
                            break;
                        case Field::TYPE_FLOAT:
                            $existingField->fieldFloat()->update(
                                ['value' => $field['value']]);
                            break;
                        case Field::TYPE_BOOLEAN:
                            $existingField->fieldBool()->update(
                                ['value' => $field['value']]);
                            break;
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Note update error', [
                'error-message' => $e->getMessage(),
            ]);
            return null;
        }
        return $note;
    }

}
