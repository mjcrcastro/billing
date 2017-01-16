<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //items table used to centralize data about products
        Schema::create('products', function($table) {
            $table->increments('id');
            $table->integer('product_type_id')
                          ->index()
                          ->references('id')
                          ->on('product_types')
                          ->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
        Schema::drop('products');
    }

}
