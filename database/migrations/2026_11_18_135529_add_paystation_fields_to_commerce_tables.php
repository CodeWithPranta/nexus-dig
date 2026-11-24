<?php

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
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('service_id')
                ->nullable()
                ->after('course_id')
                ->constrained()
                ->nullOnDelete();
            $table->string('item_type')->default('course')->after('service_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('gateway_reference')->nullable()->after('transaction_ref')->index();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_id');
            $table->dropColumn('item_type');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('gateway_reference');
        });
    }
};
