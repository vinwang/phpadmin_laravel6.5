<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('comment')->comment('操作内容');
            $table->bigInteger('user_id')->comment('操作人');
            $table->tinyInteger('read')->default(0)->comment('0未读,1已读');
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
        Schema::dropIfExists('system_log');
    }
}
