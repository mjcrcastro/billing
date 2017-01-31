<?php

use Illuminate\Database\Seeder;

class StoragesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('storages')->insert(array(
           array('id'=>1,'description'=>"San Carlos",'created_at'=>"2015-03-23",
               'updated_at'=>"2015-03-23",) 
            
        ));
    }
}
