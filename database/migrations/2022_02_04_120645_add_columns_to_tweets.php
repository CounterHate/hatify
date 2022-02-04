<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTweets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tweets', function (Blueprint $table) {
            $table->text('other_topic')->default(null)->nullable();
            $table->boolean('is_hate_speech')->default(true);
            $table->foreignId('user_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tweets', function (Blueprint $table) {
            //
        });
    }
}
