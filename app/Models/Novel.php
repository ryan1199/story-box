<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Novel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function boxes()
    {
        return $this->belongsToMany(Box::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function report(): MorphOne
    {
        return $this->morphOne(Report::class, 'reportable');
    }
}
