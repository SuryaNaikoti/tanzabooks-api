<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();

            $table->string('fileable_type');
            $table->integer('fileable_id');
            $table->string('file_url')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('file_name');
            $table->integer('file_size');
            $table->string('extension', 10)->nullable();
            $table->string('type', 15);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
