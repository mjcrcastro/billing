<?php

use Illuminate\Database\Seeder;

class DescriptorTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//preconfigured descriptor types
        DB::table('descriptor_types')->insert(array(
           array('id'=>1,'description'=>"Generic Name",'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
           array('id'=>2,'description'=>"Marca",'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
           array('id'=>3,'description'=>"Empaque",'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
           array('id'=>4,'description'=>"Peso",'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
           array('id'=>5,'description'=>"Modelo",'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",), 
            
        ));
    }
}
