<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * `menus` is the CMS-editable menu data. The 8-section navbar shape is hardcoded;
     * this table provides labels + ordering + visibility for submenu items.
     * `route_name` is constrained at app level to a whitelist of registered routes —
     * admins cannot invent arbitrary route paths.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->nullOnDelete();
            $table->string('group', 32)->index();      // beranda|profil|layanan|aplikasi|statistik|informasi|pengaduan|kontak|footer
            $table->string('label');
            $table->string('route_name')->nullable();  // bound to a whitelist of named routes
            $table->string('external_url')->nullable();
            $table->string('icon', 64)->nullable();
            $table->boolean('is_visible')->default(true)->index();
            $table->boolean('open_in_new_tab')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
