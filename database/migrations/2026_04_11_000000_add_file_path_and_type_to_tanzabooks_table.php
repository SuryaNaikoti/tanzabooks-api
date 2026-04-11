<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilePathAndTypeToTanzabooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tanzabooks', function (Blueprint $table) {
            if (!Schema::hasColumn('tanzabooks', 'file_path')) {
                $table->string('file_path')->nullable()->after('file_id');
            }
            if (!Schema::hasColumn('tanzabooks', 'type')) {
                $table->string('type')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tanzabooks', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'type']);
        });
    }
}
