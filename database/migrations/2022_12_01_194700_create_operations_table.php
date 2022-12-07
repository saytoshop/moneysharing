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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->string('type')->nullable();
            $table->boolean('storno')->default(0);
            $table->integer('storno_id')->nullable();
            $table->float('amount');
            $table->integer('user_id')->nullable();
            $table->integer('operator_id')->nullable();
            $table->string('comment')->nullable();
            $table->integer('operation_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operations');
    }
};
