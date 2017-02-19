<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvTransactionDetail extends Model
{
    //which table to attach to
    protected $table = 'inv_transaction_details';
    protected $guarded = array('id');
    
    public function invTransactionHeader() {
        return $this->belongsTo('App\InvTransactionHeader');
    }
    
    public function product() {
        return $this->hasOne('App\Product');
    }
}
