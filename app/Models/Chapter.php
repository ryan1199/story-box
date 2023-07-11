<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'novel_id',
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function novel()
    {
        return $this->belongsTo(Novel::class);
    }
}
