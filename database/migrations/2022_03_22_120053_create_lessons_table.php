<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            $table->string('lesson_name');
            $table->string('lesson_heading');
            $table->string('class_room_subject_id');
            $table->enum('type', ['classroom', 'private'])->default('classroom');
            $table->boolean('status')->default(1);

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
