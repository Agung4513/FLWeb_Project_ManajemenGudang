<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->decimal('price_at_transaction', 18, 2)->after('quantity')->default(0);
            $table->decimal('subtotal', 18, 2)->after('price_at_transaction')->default(0);
        });
    }

    public function down()
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn(['price_at_transaction', 'subtotal']);
        });
    }
};
