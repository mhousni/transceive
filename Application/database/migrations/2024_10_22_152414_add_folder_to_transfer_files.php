<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_files', function (Blueprint $table) {
            $table->bigInteger('folder_id')->unsigned()->nullable()->default(0);
            $table->foreign("folder_id")->references("id")->on('folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_files', function (Blueprint $table) {
            Schema::dropIfExists('transfer_files');
        });
    }
};
