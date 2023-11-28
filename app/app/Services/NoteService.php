<?php

namespace App\Services;

use App\Http\Resources\NoteResource;
use App\Jobs\SendEmailJob;
use App\Models\Field;
use App\Models\FieldString;
use App\Models\Note;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка создания заметки', [
                'error-message' => $e->getMessage(),
                'note-data' => $data,
            ]);
            return null;
        }
        return $note;
    }

    /** Обновление заметки.
     *
     * @param array $data принимает массив с полями для редактирования заметки.
     * @return null
     */
    public function update(array $data)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $note = Note::where('user_id', $user->id)
                ->update([
                    'title' => $data['title'],
                ]);

            foreach ($data['fields'] as $field) {
                $existingField = Field::where('id', $field['id'])->first();
                $existingField->update(['title' => $field['title']]);

                switch ($existingField['type']) {
                    case 'string':
                        $existingField->fieldString()->update(
                            ['value' => $field['value']]);
                        break;
                    case 'integer':
                        $existingField->fieldInt()->update(
                            ['value' => $field['value']]);
                        break;
                    case 'float':
                        $existingField->fieldFloat()->update(
                            ['value' => $field['value']]);
                        break;
                    case 'boolean':
                        $existingField->fieldBool()->update(
                            ['value' => $field['value']]);
                        break;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка обновления заметки', [
                'error-message' => $e->getMessage(),
            ]);
            return null;
        }
        return $note;
    }

}
