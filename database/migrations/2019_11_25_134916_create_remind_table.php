<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remind', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content');
            $table->timestamp('remind_time')->nullable()->comment('提醒时间');
            $table->smallInteger('repeat')->default(0)->comment('0不重复，1每天，2每周，3每月，4每年');
            $table->smallInteger('status')->default(0)->comment('0未提醒，1已完成，2已过期');
            $table->bigInteger('admin_id')->comment('创建人');
            $table->string('model')->comment('提醒来源模型');
            $table->timestamps();
        });

        Schema::create('user_remind', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('remind_id');
        });
        Schema::create('customer_remind', function (Blueprint $table) {
            $table->bigInteger('customer_id');
            $table->bigInteger('remind_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remind');
        Schema::dropIfExists('user_remind');
        Schema::dropIfExists('customer_remind');
    }
}
