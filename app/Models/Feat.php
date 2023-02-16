<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feat extends Model
{
    use HasFactory;
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;


    protected $fillable = ['name'];

    public function musics()
    {
        return $this->belongsToMany(Music::class);
    }
}
