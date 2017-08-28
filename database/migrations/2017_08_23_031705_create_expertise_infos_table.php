<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpertiseInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('expertise_infos')) {
            Schema::create('expertise_infos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('expertise_id');
                $table->integer('expertise_speciality_id');
                $table->string('category_of_difficulty')->nullable()->default(null);
                $table->integer('questions_count')->default(0);
                $table->integer('objects_count')->default(0);
                $table->string('expiration_date')->nullable()->default(null);
                $table->string('reason_for_suspension')->nullable()->default(null);
                $table->string('suspension_date')->nullable()->default(null);
                $table->string('renewal_date')->nullable()->default(null);
                $table->integer('document_id')->default(0);
                $table->integer('correspondence_id')->default(0);
                $table->string('finish_date')->nullable()->default(null);
                $table->integer('actual_days')->default(0);
                $table->integer('production_days')->default(0);
                $table->string('result_of_research')->nullable()->default(0);
                $table->integer('categorical_conclusions')->default(0);
                $table->integer('probable_conclusions')->default(0);
                $table->integer('wnp')->default(0);
                $table->integer('unsolved_issues_count')->default(0);
                $table->integer('conclusions_count')->default(0);
                $table->integer('categorical_conclusions_positive')->default(0);
                $table->integer('categorical_conclusions_negative')->default(0);
                $table->integer('cost')->default(0);
                $table->string('payment_note')->nullable()->default(null);
                $table->string('payment_note_document_number')->nullable()->default(null);
                $table->string('payment_note_document_date')->nullable()->default(null);
                $table->text('return_reason')->nullable()->default(null);
                $table->text('rigs')->nullable()->default(null);
                $table->integer('user_id');
                $table->integer('parent_id')->default(0);
                $table->boolean('status')->default(0);
                $table->boolean('is_stopped')->default(0);
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
        Schema::dropIfExists('expertise_infos');
    }
}
