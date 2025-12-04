<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('restock_orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed_by_supplier', 'in_transit', 'received'])
                  ->default('pending')->change();

            $table->timestamp('confirmed_by_supplier_at')->nullable();
            $table->text('supplier_notes')->nullable();
        });
    }

    public function down()
    {
        Schema::table('restock_orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'in_transit', 'received'])->default('pending')->change();
            $table->dropColumn(['confirmed_by_supplier_at', 'supplier_notes']);
        });
    }
};
