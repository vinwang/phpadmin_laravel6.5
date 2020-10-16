<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderActionRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_action_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->comment('当前订单'); 
            $table->integer('user_id')->comment('当前用户名称'); 
            $table->string('content')->comment('分配名称'); 
            $table->text('remarks')->nullable()->comment('备注说明');
            $table->timestamps();

            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_action_record');
    }
}
