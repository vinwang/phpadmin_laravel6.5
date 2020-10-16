<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('refund_money')->comment('退款金额');
            $table->text('remark')->nullable()->comment('备注');
            $table->integer('order_id')->comment('订单id'); 
            $table->timestamp('refund_time')->nullable()->comment('退款时间'); 
            $table->tinyInteger('status')->default(0)->comment('审核状态：0 待审核 1 审核通过 2 审核未通过');
            $table->text('reason')->nullable()->comment('审核备注');
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
        Schema::dropIfExists('refund');
    }
}
