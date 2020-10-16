<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersMaintenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_maintenance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->comment('公司名称id');
            $table->string('order_id')->comment('对应订单id');
            $table->text('device_msg')->nullable()->comment('业务种类、节点、厂商、Sn号、地址');
            $table->Integer('contract_starttime')->nullable()->comment('合同起始时间');
            $table->Integer('contract_endtime')->nullable()->comment('合同结束时间');
            $table->decimal('xinan_money', 8, 0)->default(0)->comment('信安金额');
            $table->decimal('deposit_money', 8, 0)->default(0)->comment('托管金额');
            $table->decimal('upgrade_money', 8, 0)->default(0)->comment('升级金额');
            $table->decimal('record_money', 8, 0)->default(0)->comment('备案金额');
            $table->decimal('cost_money', 8, 0)->default(0)->comment('成本金额');
            $table->decimal('total', 8, 0)->default(0)->comment('总金额');
            $table->Integer('contract_send_time')->nullable()->comment('合同寄出时间');
            $table->Integer('contract_back_time')->nullable()->comment('合同寄回时间');
            $table->Integer('receipt_send_time')->nullable()->comment('发票寄出时间');
            $table->Integer('payment_time')->nullable()->comment('付款时间');
            $table->text('remarks')->nullable()->comment('备注');
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
        Schema::dropIfExists('orders_maintenance');
    }
}
