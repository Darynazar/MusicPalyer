<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;


    protected $fillable = [
        'src', 
        'demo', 
        'title', 
        'cover', 
        'artist_id', 
        'category_id', 
        'feat_id', 
        'play', 
        'view', 
        'heart', 
        'musicDuration',
        'demoDuration',
        'top'
    ];

    public function feats()
    {
        return $this->belongsToMany(Feat::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function likes()
    {
        //return $this->belongsToMany(Like::class);
        
        return $this->hasMany(Like::class); //convert to hasMany
    }
}
