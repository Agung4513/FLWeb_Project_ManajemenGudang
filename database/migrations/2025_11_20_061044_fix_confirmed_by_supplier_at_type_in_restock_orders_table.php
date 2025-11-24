<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE restock_orders SET confirmed_by_supplier_at = NULL WHERE confirmed_by_supplier_at = '' OR confirmed_by_supplier_at IS NULL OR confirmed_by_supplier_at = '0000-00-00 00:00:00'");

        Schema::table('restock_orders', function (Blueprint $table) {
            $table->dateTime('confirmed_by_supplier_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('restock_orders', function (Blueprint $table) {
            $table->string('confirmed_by_supplier_at')->nullable()->change();
        });
    }
};
