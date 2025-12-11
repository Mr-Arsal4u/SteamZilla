<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page'); // home, about, contact
            $table->string('section'); // hero, benefits, how_it_works, etc.
            $table->string('key'); // title, subtitle, description, etc.
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, image, html, json
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->unique(['page', 'section', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};
