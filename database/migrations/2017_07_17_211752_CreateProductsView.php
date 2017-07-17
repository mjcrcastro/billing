<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("Create view products_descriptions AS 
            SELECT products_descriptors.product_id as id, 
            GROUP_CONCAT(DISTINCT descriptors.description 
                ORDER BY descriptors.descriptor_type_id SEPARATOR ' ') AS description
            FROM descriptors INNER JOIN products_descriptors 
            ON descriptors.id = products_descriptors.descriptor_id
            Group By products_descriptors.product_id");
        
        DB::statement("create view productsview AS
            SELEcT p.*, sum(itd.product_qty*tt.effect_inv) AS totalQty,
                        sum(itd.product_cost*tt.effect_inv) AS totalCost,
                        (SELECT products_descriptions.description from products_descriptions where products_descriptions.id = p.id) AS description
            from products aS p
            Inner join inv_transaction_details AS itd ON itd.product_id = p.id
            Inner join inv_transaction_headers AS ith ON itd.inv_transaction_header_id = ith.id
            Inner join transaction_types as tt ON ith.transaction_type_id = tt.id
            group By p.id");
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("DROP VIEW products_descriptions");
        DB::statement("DROP VIEW ProductsView");
        
    }
}
