<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldInt extends Model
{
    use HasFactory;

    protected $table = 'field_int';

    protected $fillable = [
        'value',
        'field_id'
    ];

    public function field()
    {
        return $this->hasOne(Field::class,'id','field_id');
    }
}
