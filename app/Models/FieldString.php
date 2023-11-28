<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldString extends Model
{
    use HasFactory;

    protected $table = 'field_string';

    protected $fillable = [
        'value',
    ];

    public function field()
    {
        return $this->hasOne(Field::class,'id','field_id');
    }
}
