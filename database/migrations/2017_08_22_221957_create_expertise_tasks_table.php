<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpertiseTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('expertise_tasks')) {
            Schema::create('expertise_tasks', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('executor_id');
                $table->string('speciality_ids');
                $table->integer('expertise_id');
                $table->integer('parent_id')->default(0);
                $table->tinyInteger('status')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expertise_tasks');
    }
}
