<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('user_voucher', function (Blueprint $table) {
        $table->integer('uses_remaining')->default(1)->after('used');
    });
}

public function down()
{
    Schema::table('user_voucher', function (Blueprint $table) {
        $table->dropColumn('uses_remaining');
    });
}

};
