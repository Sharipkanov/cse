<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->increments('id');
                $table->string('register_number')->nullable()->default(null);
                $table->string('name');
                $table->integer('document_type_id');
                $table->integer('nomenclature_id');
                $table->string('files')->nullable()->default(null);
                $table->text('info')->nullable()->default(null);
                $table->integer('user_id');
                $table->integer('task_id')->default(0);
                $table->integer('expertise_id')->default(0);
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
        Schema::dropIfExists('documents');
    }
}
