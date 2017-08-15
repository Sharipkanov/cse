<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpertisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expertises', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('case_number');
            $table->string('article_number');
            $table->string('expertise_primary_status');
            $table->string('expertise_status');
            $table->string('expertise_additional_status');
            $table->string('expertise_speciality_ids');
            $table->integer('expertise_region_id');
            $table->integer('expertise_agency_id');
            $table->integer('expertise_organ_id');
            $table->string('expertise_organ_name')->nullable()->default(null);
            $table->string('expertise_user_fullname');
            $table->string('expertise_user_position')->nullable()->default(null);
            $table->string('expertise_user_rank')->nullable()->default(null);
            $table->text('info');
            $table->string('files')->nullable()->default(null);
            $table->integer('user_id');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('expertises');
    }
}
