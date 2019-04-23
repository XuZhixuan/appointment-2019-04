<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeappOpenIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('weapp_openid')->unique()->nullable()->after('password')->comment('微信小程序登录ID');
            $table->string('weapp_session_key')->nullable()->nullable()->after('weapp_openid');
            $table->string('password')->default('*')->change();
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
            $table->dropColumn('weapp_openid');
            $table->dropColumn('weapp_session_key');
            $table->string('password')->default(null)->change();
        });
    }
}
