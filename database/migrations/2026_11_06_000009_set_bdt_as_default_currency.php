<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('currency', 3)->default('BDT')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('currency', 3)->default('BDT')->change();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('currency', 3)->default('USD')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('currency', 3)->default('USD')->change();
        });
    }
};
