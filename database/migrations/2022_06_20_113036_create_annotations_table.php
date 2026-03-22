<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('annotations', function (Blueprint $table) {
            $table->id();

            $table->integer('tanzabook_id');
            $table->integer('user_id');
            $table->string('file')->nullable();
            $table->longText('data');
            $table->longText('comment');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('annotations');
    }
};
