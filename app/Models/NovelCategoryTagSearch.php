<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovelCategoryTagSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tags',
        'categories',
        'novel_id'
    ];

    public function search()
    {
        return $this->belongsTo(Novel::class);
    }
}
