<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tanzabooks', function (Blueprint $table) {
            if (!Schema::hasColumn('tanzabooks', 'group_id')){
                $table->integer('group_id')->nullable()->after('folder_id');
            }
        });
    }

    public function down()
    {
        Schema::table('tanzabooks', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
};
