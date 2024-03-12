<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stk')->nullable()->after('email');
            $table->string('noi_dung_ck')->nullable()->after('email');
            $table->string('loai_ngan_hang')->nullable()->after('email');
            $table->string('ten_cua_hang')->nullable()->after('email');
            $table->string('dia_chi')->nullable()->after('email');
            $table->string('phone')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stk', 'noi_dung_ck', 'loai_ngan_hang', 'ten_cua_hang', 'dia_chi', 'phone']);
        });
    }
}
