<?php

namespace App\Observers;

use App\Models\Novel;
use Illuminate\Support\Str;

class NovelObserver
{
    public $afterCommit = true;
    /**
     * Handle the Novel "created" event.
     */
    public function created(Novel $novel): void
    {
        // $novel->slug = Str::slug($novel->title).'-'.strtotime("now");
        // $novel->save();
    }

    /**
     * Handle the Novel "updated" event.
     */
    public function updated(Novel $novel): void
    {
        // $novel->slug = Str::slug($novel->title).'-'.strtotime("now");
        // $novel->save();
    }

    /**
     * Handle the Novel "deleted" event.
     */
    public function deleted(Novel $novel): void
    {
        //
    }

    /**
     * Handle the Novel "restored" event.
     */
    public function restored(Novel $novel): void
    {
        //
    }

    /**
     * Handle the Novel "force deleted" event.
     */
    public function forceDeleted(Novel $novel): void
    {
        //
    }
}
