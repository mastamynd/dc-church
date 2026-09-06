<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMpesaReceiptToPaymentDivisions extends Migration
{
    public function up()
    {
        $table = Schema::hasTable('payment_divisions')
            ? 'payment_divisions'
            : (Schema::hasTable('payment_division') ? 'payment_division' : null);

        if (!$table || Schema::hasColumn($table, 'mpesa_receipt')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->string('mpesa_receipt')->nullable();
        });
    }

    public function down()
    {
        $table = Schema::hasTable('payment_divisions')
            ? 'payment_divisions'
            : (Schema::hasTable('payment_division') ? 'payment_division' : null);

        if (!$table || !Schema::hasColumn($table, 'mpesa_receipt')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->dropColumn('mpesa_receipt');
        });
    }
}
