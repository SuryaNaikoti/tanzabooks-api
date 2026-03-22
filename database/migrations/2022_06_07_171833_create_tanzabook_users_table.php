<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTanzabookUsersTable extends Migration
{
    public function up()
    {
        Schema::create('tanzabook_users', function (Blueprint $table) {
            $table->id();

            $table->integer('tanzabook_id');
            $table->integer('user_id');
            $table->string('shared_type')->default('user');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tanzabook_users');
    }
}
