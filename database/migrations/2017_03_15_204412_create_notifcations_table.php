<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifcationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('notifications', function (Blueprint $table) {
            $table->string('id');
            $table->string('reference_number')->nullable();
            $table->string('message');
            $table->integer('viewed')->unsigned();
            $table->string('type');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->drop('notifications');
    }
}
