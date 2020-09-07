<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusesToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('status_id')->default(1)->after('role_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('status_id')->references('id')->on('user_statuses');
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
            $table->dropForeign(['status_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status_id');
        });
    }
}
