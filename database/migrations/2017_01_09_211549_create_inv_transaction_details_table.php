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
                $table->integer('transaction_header_id')
                        ->index()
                        ->references('id')
                        ->on('transaction_header');
                $table->integer('item_id'); //item being moved
                $table->float('item_qty');  //quantity of items being moved
                $table->float('item_cost'); //total cost of items being moved
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
