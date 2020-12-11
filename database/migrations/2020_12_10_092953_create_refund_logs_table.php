<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundLogsTable extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('refund_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('refund_id');
            $table->morphs('userable');
            $table->string('pictures')->nullable()->comment('图片');
            $table->string('title')->nullable()->comment('标题');
            $table->string('remark')->nullable()->comment('详情');
            $table->boolean('type')->default(1);
            $table->string('state', 16)->comment('状态')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_logs');
    }

}
