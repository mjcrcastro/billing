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
        return $this->hasMany('App\ProductDescriptor')
                        ->join('descriptors', 'descriptors.id', '=', 'products_descriptors.descriptor_id')
                        ->orderBy('descriptors.descriptor_type_id');
    }

    public function productType() {
        return $this->belongsTo('\App\ProductType', 'product_type_id');
    }

    public static function boot() {
        parent::boot();
        static::deleted(function($product) {
            $product->productDescriptors()->delete();
        });
    }

}
