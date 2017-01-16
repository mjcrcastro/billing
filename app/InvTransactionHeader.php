<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvTransactionHeader extends Model
{
    //which table to attach to
    protected $table = 'inv_transaction_headers';
    protected $guarded = array('id');
    
    //which field are used for mass assigment
    protected $fillable = [
        'description'
    ];
    
    public static $rules= array(
        'transaction_type_id' => 'required',
        'document_date' => 'required',
        'document_number' => 'required'
    );
}
