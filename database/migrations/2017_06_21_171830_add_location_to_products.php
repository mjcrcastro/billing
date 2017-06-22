<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add location to products table
        Schema::table('products', function($table) {
        $table->integer('location_id');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop location_id from products
        Schema::table('products', function($table) {
        $table->dropColumn('location_id')
                ->after('product_type_id')
                ->default(1);
    });
    }
}
