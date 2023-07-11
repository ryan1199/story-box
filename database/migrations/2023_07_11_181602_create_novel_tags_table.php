<?php

use App\Models\Novel;
use App\Models\Tag;
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
        Schema::create('novel_tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Novel::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Tag::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novel_tags');
    }
};
