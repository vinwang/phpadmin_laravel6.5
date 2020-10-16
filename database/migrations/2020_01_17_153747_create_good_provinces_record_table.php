<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodProvincesRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_provinces_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->comment('当前订单id'); 
            $table->integer('goodprovince_id')->comment('当前业务节点状态id'); 
            $table->integer('user_id')->comment('操作人'); 
            $table->string('content')->comment('操作业务状态记录'); 
            $table->text('remarks')->nullable()->comment('备注说明');
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
        Schema::dropIfExists('good_provinces_record');
    }
}
