<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('discussions')) {
            return;
        }
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();

            $table->integer('discussable_id')->nullable();
            $table->string('discussable_type')->nullable();
            $table->integer('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
            $table->string('file_id')->nullable();
            $table->string('comment')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discussions');
    }
}
