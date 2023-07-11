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

    public function boxes()
    {
        return $this->belongsToMany(Box::class);
    }

    public function novels()
    {
        return $this->belongsToMany(Novel::class);
    }
}
