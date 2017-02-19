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

    public function productDescriptors() {
        return $this->hasMany('App\ProductDescriptor');
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
