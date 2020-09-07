<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivateTokenByEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activate_token_by_emails', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->unique();
            $table->string('token', 60)->unique();
            $table->dateTime('expire_time');
        });

        Schema::table('user_activate_token_by_emails', function ($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_activate_token_by_emails', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('user_activate_token_by_emails');
    }
}
