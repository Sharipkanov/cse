<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorrespondencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('correspondences')) {
            Schema::create('correspondences', function (Blueprint $table) {
                $table->increments('id');
                $table->string('register_number')->nullable()->default(null);
                $table->integer('language_id');
                $table->integer('correspondent_id');
                $table->string('outcome_number')->nullable()->default(null);
                $table->string('outcome_date')->nullable()->default(null);
                $table->string('executor_fullname');
                $table->string('execution_period')->nullable()->default(null);
                $table->integer('pages');
                $table->string('files')->nullable()->default(null);
                $table->integer('reply_correspondence_id')->default(0);
                $table->integer('recipient_id')->default(0);
                $table->integer('document_type_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->integer('document_id')->default(0);
                $table->boolean('is_income')->default(0);
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
        Schema::dropIfExists('correspondences');
    }
}
