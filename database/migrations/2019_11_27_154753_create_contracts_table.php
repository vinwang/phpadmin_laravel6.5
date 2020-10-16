<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->default(0)->comment('合同类型 0合同 1资质');
            $table->integer('customer_id')->comment('客户id');
            $table->integer('order_id')->comment('订单id');
            $table->string('title')->comment('合同标题');
            $table->string('number')->nullable()->comment('合同编号');
            $table->integer('starttime')->nullable()->comment('开始时间');
            $table->integer('endtime')->nullable()->comment('截止时间');
            $table->text('remark')->nullable()->comment('合同备注');
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
        Schema::dropIfExists('contracts');
    }
}
