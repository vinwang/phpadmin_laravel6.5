<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company',255);
            $table->string('address',255)->nullable();
            $table->bigInteger('source_id');
            $table->bigInteger('uid');
            $table->string('name',100);
            $table->smallInteger('sex')->default(0);
            $table->string('phone',20);
            $table->string('tel',20)->nullable();
            $table->string('emil',100)->nullable();
            $table->string('job',20)->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->smallInteger('status');
            $table->text('remarks')->nullable(); 
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
        Schema::dropIfExists('customer');
    }
}
