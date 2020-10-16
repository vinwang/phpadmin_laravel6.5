<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_cost', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('business_cost')->default(0)->unsigned()->comment('商务费用');
            $table->integer('outsourcing_cost')->default(0)->unsigned()->comment('外包成本');
            $table->integer('urgent_cost')->default(0)->unsigned()->comment('加急费用');
            $table->integer('xiaoshou_other_cost')->default(0)->unsigned()->comment('销售部其他成本');
            $table->integer('equipment_cost')->default(0)->unsigned()->comment('设备成本');
            $table->integer('trusteeship_cost')->default(0)->unsigned()->comment('托管费用');
            $table->integer('software_cost')->default(0)->unsigned()->comment('软件费用');
            $table->integer('jishu_other_cost')->default(0)->unsigned()->comment('技术部其他成本');
            $table->text('jishu_remarks')->nullable()->comment('技术部备注');
            $table->bigInteger('order_id')->default(0)->unsigned()->comment('订单编号');
            $table->timestamps();
        });

        //去除订单表中原商务费用
        Schema::table('orders', function (Blueprint $table) {
            if(Schema::hasColumn('orders', 'spend')){
                $table->dropColumn('spend');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_cost');
    }
}
