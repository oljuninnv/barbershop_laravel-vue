<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('records', function (Blueprint $table) {
            $table->boolean('is_finished')->nullable();
        });
    }

    public function down()
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropColumn('is_finished');
        });
    }
};
