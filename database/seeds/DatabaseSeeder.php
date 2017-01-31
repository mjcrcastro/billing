<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // $this->call(UsersTableSeeder::class);
        $this->call('DescriptorTypesTableSeeder');
        $this->call('ProductTypesSeeder');
        $this->call('TransactionTypesSeeder');
        $this->call('StoragesTableSeeder');
        $this->call('DescriptorsTableSeeder');
    }

}
