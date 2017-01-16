<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTypesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transaction_types', function(Blueprint $table) {
            $table->increments('id');
            $table->string('short_description'); //example FCRE, PROF, FCON
            $table->string('description'); //Long description when possible
            $table->integer('effect_inv'); //+1, -1, 0
            //a transaction might require value, quantity, either or none
            $table->boolean('req_qty')->default(true); //requires quantity
            $table->boolean('req_val')->default(true); //requires value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('transaction_types');
    }

}
