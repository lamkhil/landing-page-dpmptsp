<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Single posts table with `type` discriminator (news, announcement, article,
     * agenda, regulation, profil, zi-content, infografis). Per-type behavior lives
     * in Service classes — keeps query patterns consistent.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32)->index();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('body')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('status', 16)->default('draft')->index();   // draft|published|archived
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('scheduled_at')->nullable()->index();
            // SEO overrides per post (kept typed via DTO at app layer):
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('og_image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
