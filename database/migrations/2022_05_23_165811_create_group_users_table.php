<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupUsersTable extends Migration
{
    public function up()
    {
        Schema::create('group_users', function (Blueprint $table) {
            $table->id();

            $table->integer('group_id');
            $table->integer('user_id');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_users');
    }
}
