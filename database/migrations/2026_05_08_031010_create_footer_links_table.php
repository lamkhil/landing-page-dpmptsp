<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('footer_links', function (Blueprint $table) {
            $table->id();
            $table->string('group', 32)->index();   // quick|service|external|partner
            $table->string('label');
            $table->string('url');
            $table->boolean('open_in_new_tab')->default(false);
            $table->boolean('is_visible')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_links');
    }
};
