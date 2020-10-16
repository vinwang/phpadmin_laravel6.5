<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('order_type')->nullable()->default(1)->comment('订单类型 1:办证 2:变更 3:年审');
            $table->string('order_num')->comment('订单编号');
            $table->integer('customer_id')->comment('客户id');
            $table->string('increment')->nullable()->comment('增值服务');
            $table->string('licence')->nullable()->comment('许可证号');
            $table->integer('licence_start')->nullable()->comment('许可证有效期(起始时间)');
            $table->integer('licence_end')->nullable()->comment('许可证有效期(截止时间)');
            $table->integer('user_id')->comment('归属人id');
            $table->string('process',20)->default(1)->comment('业务状态');
            $table->text('notes')->nullable()->comment('文案部业务状态备注');
            $table->string('plannedamt')->default(0)->comment('合同金额');
            $table->text('stages')->comment('分期金额及开票时间，收款时间');
            $table->text('content')->nullable()->comment('开票备注');
            $table->integer('jsverify')->default(0)->comment('技术部审核状态,0待审核，1审核通过，2审核未通过，3退回，4已完成');
            $table->integer('waverify')->default(0)->comment('文案部审核状态，0待审核，1审核通过，2审核未通过，3退回，4已完成');
            $table->text('js_content')->nullable()->comment('技术部审核说明');
            $table->text('wa_content')->nullable()->comment('文案部审核说明');
            $table->text('remarks')->nullable()->comment('备注');
            $table->tinyInteger('status')->nullable()->default(0)->comment('0新建,1关闭,2完成,3待审核,4审核通过,5审核未通过');
            $table->tinyInteger('shipped')->default(1)->comment('0未发货,1发货');
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
        Schema::dropIfExists('orders');
    }
}
