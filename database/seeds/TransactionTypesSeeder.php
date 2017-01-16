<?php

use Illuminate\Database\Seeder;

class TransactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_types')->insert(array(
           array('id'=>1,
               'short_description'=>"ENT_INV",
               'description'=>"Entrada de Inventario",
               'effect_inv'=>'1',
               'req_qty'=>TRUE,
               'req_val'=>TRUE,
               'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
           array('id'=>2,
               'short_description'=>"SAL_INV",
               'description'=>"Entrada de Inventario",
               'effect_inv'=>'-1',
               'req_qty'=>TRUE,
               'req_val'=>TRUE,
               'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",), 
        ));
    }
}
