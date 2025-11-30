<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('restock_orders')->update(['confirmed_by_supplier_at' => null]);

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
