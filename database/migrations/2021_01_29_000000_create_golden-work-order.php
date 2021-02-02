<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGoldenWorkOrderAudit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('golden_work_order_audits', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('target_id')->comment('目标id');
            $table->string('target_type', 191)->comment('目标类型');
            $table->unsignedInteger('work_order_id')->comment('工单id');
            $table->unsignedInteger('work_order_status')->comment('工单状态');
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
        Schema::dropIfExists('golden_work_order_audits');
    }
}
