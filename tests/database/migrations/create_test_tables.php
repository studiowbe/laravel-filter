<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestTables extends Migration
{
    public function up()
    {
        Schema::dropIfExists('cars');

        Schema::create('cars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('c');
            $table->text('owner')->nullable();
        });
    }

    public function down()
    {
    }
}
