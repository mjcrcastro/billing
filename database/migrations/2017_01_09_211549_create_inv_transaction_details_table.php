<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Contains transaction details
               Schema::create('inv_transaction_details', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('inv_transaction_header_id')
                        ->index()
                        ->references('id')
                        ->on('transaction_header');
                $table->integer('product_id')
                        ->index()
                        ->references('id')
                        ->on('products'); //item being moved
                $table->float('product_qty');  //quantity of items being moved
                $table->float('product_cost'); //total cost of items being moved
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
        Schema::drop('inv_transaction_details');
    }
}
