<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiveAssignRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_assign_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type')->default(0)->comment('0新增 1领取 2分配 3退回');
            $table->bigInteger('customer_id')->comment('客户id');
            $table->bigInteger('person_id')->nullable()->comment('原归属人/被分配人/被退回人id');
            $table->bigInteger('user_id')->comment('操作人id,0为自动退回');
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
        Schema::dropIfExists('receive_assign_record');
    }
}
