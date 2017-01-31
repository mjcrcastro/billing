<?php

use Illuminate\Database\Seeder;

class DescriptorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('descriptors')->insert(array(
           array('id'=>1,'description'=>"Laptop",
               'descriptor_type_id'=>1,
               'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
           array('id'=>2,'description'=>"Dell",
               'descriptor_type_id'=>2,
               'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
            array('id'=>3,'description'=>"Precisiono M4800",
               'descriptor_type_id'=>5,
               'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",),
            
        ));
    }
}
