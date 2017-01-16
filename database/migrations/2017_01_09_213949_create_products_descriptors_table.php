<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsDescriptorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //For storing the descriptors of a given product
            Schema::create('products_descriptors', function($table) {
            $table->increments('id');
            $table->integer('product_id')
                    ->index()->references('id')->on('products');
            $table->integer('descriptor_id')
                    ->index()->references('id')->on('descriptors');
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
        Schema::drop('products_descriptors');
    }
}
