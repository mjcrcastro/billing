<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescriptorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Add descriptors table
            
                 Schema::create('descriptors', function($table) {
                   $table->increments('id');
                   $table->integer('descriptor_type_id')
                           ->index()->references('id')->on('descriptor_types');
                   $table->string('description');
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
        //
        Schema::drop('descriptors');
    }
}
