<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDescriptor extends Model
{
    //which table to attach to
    protected $table = 'products_descriptors';
    protected $guarded = array('id');
    // $ fillable are fields that can be send as input
    public static $rules = array(
        'descriptor_id' => 'required',
        'product_id' => 'required',
    );
    
    public function descriptor() {
        return $this->belongsTo('App\Descriptor');
    }
}
