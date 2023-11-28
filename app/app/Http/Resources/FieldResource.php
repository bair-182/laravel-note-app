<?php

namespace App\Http\Resources;

use App\Models\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

/**
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class FieldResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $arrayData = [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'title' => $this->title ?? 'No title',
            'note_id' => $this->note_id,
        ];

        switch ($this->type){
            case Field::TYPE_STRING:
                $arrayData['value'] = $this->fieldString->value ?? null;
                break;
            case Field::TYPE_INTEGER:
                $arrayData['value'] = $this->fieldInt->value ?? null;
                break;
            case Field::TYPE_FLOAT:
                $arrayData['value'] = $this->fieldFloat->value ?? null;
                break;
            case Field::TYPE_BOOLEAN:
                $arrayData['value'] = $this->fieldBool->value ?? null;
                break;
        }
        $arrayData['type'] = $this->type;

        return $arrayData;
    }
}
