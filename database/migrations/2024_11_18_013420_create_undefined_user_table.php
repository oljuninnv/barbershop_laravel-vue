<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUndefinedUserTable extends Migration
{
    public function up()
    {
        Schema::create('undefined_user', function (Blueprint $table) {
            $table->id(); // Создает поле id
            $table->string('user_name'); // Поле для имени пользователя
            $table->string('user_phone'); // Поле для телефона пользователя
            $table->string('user_email')->unique(); // Поле для email пользователя, уникальное
            $table->timestamps(); // Поля created_at и updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('undefined_user');
    }
}
