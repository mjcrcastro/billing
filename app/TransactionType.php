<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    //which table to attach to
    protected $table = 'transaction_types';
    protected $guarded = array('id');
    
    //which field are used for mass assigment
    protected $fillable = [
        'description',
        'short_description',
        'effect_inv',
        'req_qty',
        'req_val'
    ];
    
    public static $rules= array(
        'description' => 'required|unique:storages,description,null,{{$id}}'
    );
}
