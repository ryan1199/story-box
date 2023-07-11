<?php

use App\Models\Box;
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
        Schema::create('box_novel', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Box::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Novel::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box_novel');
    }
};
