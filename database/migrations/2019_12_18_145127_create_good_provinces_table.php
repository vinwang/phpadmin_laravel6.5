<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_provinces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->comment('订单ID');
            $table->integer('good_id')->comment('业务种类ID');
            $table->integer('provinces')->comment('省或市');
            $table->tinyInteger('review')->default(0)->comment('是否评测：1 评测  0 不评测');
            $table->tinyInteger('network')->default(0)->comment('是否含网：1 含网  0 不含网');
            $table->string('process',255)->default(1)->comment('业务状态');
            $table->text('content')->nullable()->comment('业务状态备注');
            $table->integer('user_id')->comment('操作人');
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
        Schema::dropIfExists('good_provinces');
    }
}
