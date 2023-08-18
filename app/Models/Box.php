<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Box extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'visible',
        'user_id',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function novels()
    {
        return $this->belongsToMany(Novel::class, 'box_novel')->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'box_tags')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'box_categories')->withTimestamps();
    }

    public function report(): MorphOne
    {
        return $this->morphOne(Report::class, 'reportable');
    }
}
