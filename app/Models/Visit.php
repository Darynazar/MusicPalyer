<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Visit extends Model
{
    use HasFactory;
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    

    protected $fillable = ['user_ip', 'music_id'];

    public function musics()
    {
        return $this->belongsToMany(Music::class);
    }
}
