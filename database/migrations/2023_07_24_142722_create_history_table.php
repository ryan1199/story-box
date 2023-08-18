<?php

use App\Models\Chapter;
use App\Models\Novel;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Novel::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('novel_title', 1000);
            $table->string('novel_slug', 5000);
            $table->foreignIdFor(Chapter::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('chapter_title', 1000);
            $table->string('chapter_slug', 5000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
