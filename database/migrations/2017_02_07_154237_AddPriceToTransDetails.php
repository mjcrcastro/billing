<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceToTransDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add column remember token
            Schema::table('inv_transaction_details',function($table){
                  $table->float('product_price')
                    ->default('0');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inv_transaction_details', function($table) {
                $table->dropColumn('product_price');
        });
    }
}
