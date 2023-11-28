<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(mixed $field)
 * @method static find(mixed $id)
 * @method static where(string $string, mixed $id)
 */
class Field extends Model
{
    use HasFactory;

    public const TYPE_STRING = 'string';

    public const TYPE_INTEGER = 'integer';
    public const TYPE_FLOAT = 'float';
    public const TYPE_BOOLEAN = 'boolean';

    public $timestamps = false;


    protected $table = 'fields';

    protected $fillable = [
        'title',
        'type',
        'note_id'
    ];

    public function note()
    {
        return $this->hasOne(Note::class,'id','note_id');
    }

    public function fieldString()
    {
        return $this->hasOne(FieldString::class,'field_id','id');
    }

    public function fieldInt()
    {
        return $this->hasOne(FieldInt::class,'field_id','id');
    }

    public function fieldFloat()
    {
        return $this->hasOne(FieldFloat::class,'field_id','id');
    }

    public function fieldBool()
    {
        return $this->hasOne(FieldBool::class,'field_id','id');
    }
}
