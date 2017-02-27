<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    protected $table = 'products';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $guarded = array('id');
    // $ fillable are fields that can be sent as input

    protected $fillable = [
        'product_type_id',
    ];
    public static $rules = array(
        'product_type_id' => 'required',
    );

    public function productDescription() {
        return $this->hasMany('App\ProductDescriptor')
                ->join('descriptors',
                        'products_descriptors.descriptor_id',
                        '=',
                        'descriptors.id')
                ->selectRaw("GROUP_CONCAT(DISTINCT descriptors.description ORDER BY descriptors.descriptor_type_id SEPARATOR ' ') AS description");
    }

    public function productType() {
        return $this->belongsTo('\App\ProductType', 'product_type_id');
    }

    public function qtyTotal() {
        return $this->hasMany('App\InvTransactionDetail')
                        ->selectRaw('sum(product_qty*transaction_types.effect_inv) AS totalQty')
                        ->join('inv_transaction_headers', 
                                'inv_transaction_details.inv_transaction_header_id', 
                                '=', 'inv_transaction_headers.id')
                        ->join('transaction_types', 
                                'inv_transaction_headers.transaction_type_id', 
                                '=', 'transaction_types.id')
                        ->groupBy('inv_transaction_details.product_id');
    }

    public function costTotal() {
        return $this->hasMany('App\InvTransactionDetail')
                ->selectRaw('sum(product_cost*transaction_types.effect_inv) AS totalCost')
                ->join('inv_transaction_headers', 
                        'inv_transaction_details.inv_transaction_header_id', 
                        '=', 'inv_transaction_headers.id')
                -> join('transaction_types', 
                        'inv_transaction_headers.transaction_type_id', 
                        '=', 'transaction_types.id')
                        ->groupBy('inv_transaction_details.product_id');
    }
    
    public function getTotalQtyAttribute () {
        $totalQty = 0;
        if (!empty($this->qtyTotal()->first()->totalQty)) {
            $totalQty = $this->qtyTotal()->first()->totalQty;
        }else{
            $totalQty = 0;
        }
        return $totalQty;
    }
    
    public function getTotalCostAttribute () {
        $totalCost = 0;
        if (!empty($this->costTotal()->first()->totalCost)) {
            $totalCost = $this->costTotal()->first()->totalCost;
        }else{
            $totalCost = 0;
        }
        return $totalCost;
    }

    public function totalSales() {
        return $this->productDetails()->sum('precio');
    }

    public static function boot() {
        parent::boot();
        static::deleted(function($product) {
            $product->productDescriptors()->delete();
        });
    }

}
