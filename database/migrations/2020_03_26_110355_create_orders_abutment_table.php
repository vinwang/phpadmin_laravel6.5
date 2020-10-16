<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersAbutmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_abutment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->comment('公司名称id');
            $table->bigInteger('order_id')->comment('对应订单id');
            $table->bigInteger('good_id')->comment('业务种类id');
            $table->bigInteger('node_id')->comment('节点id');
            $table->Integer('starttime')->nullable()->comment('对接开始时间');
            $table->Integer('endtime')->nullable()->comment('对接结束时间');
            $table->text('remark')->nullable()->comment('对接备注');
            $table->bigInteger('user_id')->comment('创建人');
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
        Schema::dropIfExists('orders_abutment');
    }
}
