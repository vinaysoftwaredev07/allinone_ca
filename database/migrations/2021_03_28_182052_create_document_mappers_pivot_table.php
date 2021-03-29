<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentMappersPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docmaps', function (Blueprint $table) {
            $table->unsignedInteger('service_id');
            $table->foreign('service_id', 'service_id_fk_1197463')->references('id')->on('services')->onDelete('cascade');
            $table->unsignedInteger('document_id');
            $table->foreign('document_id', 'document_id_fk_1197463')->references('id')->on('documents')->onDelete('cascade');
        });
    }

}
