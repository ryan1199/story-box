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
        'description',
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
        return $this->belongsToMany(Box::class, 'box_novel')->withPivot('novel_id', 'box_id')->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'novel_tags')->withPivot('novel_id', 'tag_id')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_novels')->withPivot('novel_id', 'category_id')->withTimestamps();
    }

    public function customCategories()
    {
        return $this->belongsToMany(Category::class, 'category_novels')->wherePivotIn('category_id', [3,4,5])->withPivot('novel_id', 'category_id')->withTimestamps();
    }

    public function report(): MorphOne
    {
        return $this->morphOne(Report::class, 'reportable');
    }

    public function search()
    {
        return $this->hasOne(NovelCategoryTagSearch::class);
    }
}
