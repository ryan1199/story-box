<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function boxes()
    {
        return $this->belongsToMany(Box::class, 'box_tags')->withTimestamps();
    }

    public function novels()
    {
        return $this->belongsToMany(Novel::class, 'novel_tags')->withTimestamps();
    }
}
