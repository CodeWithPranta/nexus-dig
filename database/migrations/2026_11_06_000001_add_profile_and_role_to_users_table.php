<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->index();
            $table->string('status')->default('active')->index();
            $table->string('phone')->nullable();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar_path')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'status',
                'phone',
                'headline',
                'bio',
                'avatar_path',
                'permissions',
                'approved_at',
                'last_login_at',
            ]);
        });
    }
};
