<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $data)
 * @method static find(mixed $id)
 * @method static where(string $string, $id)
 * @method static findOrFail(int $id)
 */
class Note extends Model
{
    use HasFactory;
    public $timestamps = false;



    protected $table = 'notes';

    protected $fillable = [
        'title',
        'user_id'
    ];

    public function fields()
    {
        return $this->hasMany(Field::class, 'note_id','id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'user_id','id');
    }



}
