<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no', 24)->unique();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('channel', 32)->default('web')->index();   // web|sp4n|wbs|email
            $table->string('subject');
            $table->longText('body');
            $table->string('attachment_path')->nullable();
            $table->string('status', 16)->default('open')->index();   // open|in_progress|resolved|rejected
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
