<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvTransactionDetail extends Model
{
    //which table to attach to
    protected $table = 'inv_transaction_details';
    protected $guarded = array('id');
    
    //which field are used for mass assigment
    protected $fillable = [
        'inv_transaction_header_id',
        'product_id', //item being moved
        'product_qty',  //quantity of items being moved
        'product_cost' //total cost of items being moved
    ];
    
    public function invTransactionHeader() {
        return $this->belongsTo('App\InvTransactionHeader');
    }
}
