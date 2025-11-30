<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('restock_order_id')->nullable()->after('supplier_id');

            $table->foreign('restock_order_id')->references('id')->on('restock_orders')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['restock_order_id']);
            $table->dropColumn('restock_order_id');
        });
    }
};
