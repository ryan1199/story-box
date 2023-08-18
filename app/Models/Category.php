<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
        return $this->belongsToMany(Box::class, 'box_categories')->withPivot('category_id', 'novel_id')->withTimestamps();
    }

    public function novels()
    {
        return $this->belongsToMany(Novel::class, 'box_novel')->withPivot('category_id', 'novel_id')->withTimestamps();
    }
}
