<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTanzabooksTable extends Migration
{
    public function up()
    {
        Schema::create('tanzabooks', function (Blueprint $table) {
            $table->id();

            $table->integer('folder_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('file_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tanzabook');
    }
}
