<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvTransactionHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('inv_transaction_headers', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_type_id')
                    ->index()
                    ->references('id')
                    ->on('transaction_type');
            $table->date('document_date'); //date in preprinted in document
            $table->string('document_number'); //number in preprinted document
            $table->text('note'); //any note the use would like to take down
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
        Schema::drop('inv_transaction_headers');
    }
}
