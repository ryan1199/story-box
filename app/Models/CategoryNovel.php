<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class CategoryNovel extends MorphPivot
{
    protected $table = 'category_novels';
}
