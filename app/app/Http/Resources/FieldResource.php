<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class FieldResource extends JsonResource
{
    const TYPE_STRING = 'string';
    const TYPE_FLOAT = 'float';
    const TYPE_INT = 'integer';
    const TYPE_BOOLEAN = 'boolean';


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
            case self::TYPE_STRING:
                $arrayData['value'] = $this->fieldString->value ?? null;
                break;
            case self::TYPE_INT:
                $arrayData['value'] = $this->fieldInt->value ?? null;
                break;
            case self::TYPE_FLOAT:
                $arrayData['value'] = $this->fieldFloat->value ?? null;
                break;
            case self::TYPE_BOOLEAN:
                $arrayData['value'] = $this->fieldBool->value ?? null;
                break;
        }
        $arrayData['type'] = $this->type;

        return $arrayData;
    }
}
