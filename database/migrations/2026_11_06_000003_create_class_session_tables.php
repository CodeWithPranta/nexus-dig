<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('meeting_provider')->nullable();
            $table->text('meeting_url')->nullable();
            $table->text('access_instructions')->nullable();
            $table->string('status')->default('scheduled')->index();
            $table->timestamps();
        });

        Schema::create('recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_session_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('video_url')->nullable();
            $table->string('status')->default('draft')->index();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordings');
        Schema::dropIfExists('class_sessions');
    }
};
