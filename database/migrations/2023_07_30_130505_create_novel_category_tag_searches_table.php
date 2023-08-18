<?php

use App\Models\Novel;
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
        Schema::create('novel_category_tag_searches', function (Blueprint $table) {
            $table->id();
            $table->string('title', 1000);
            $table->string('tags', 5000);
            $table->string('categories', 5000);
            $table->timestamps();
            $table->foreignIdFor(Novel::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novel_category_tag_searches');
    }
};
