<?php

use Illuminate\Database\Seeder;

class ProductTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//preconfigured product types
       DB::table('product_types')->insert(array(
           array('id'=>1,'description'=>"Productos para la Venta",'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
           array('id'=>2,'description'=>"Consumibles",'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
        ));
    }
}
