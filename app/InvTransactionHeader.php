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
        'transaction_type_id',
        'storage_id',
        'document_date',
        'document_number',
        'note'
    ];
    
    public static $rules= array(
        'transaction_type_id' => 'required',
        'storage_id'=>'required',
        'document_date' => 'required',
        'document_number' => 'required',
        'note'
    );
    
    public function transType() {
        return $this->belongsTo('\App\TransactionType', 'transaction_type_id');
    }
    
    public function invTransactionDetails() {
        return $this->hasMany('App\InvTransactionDetail');
    }
    
    //called upon being deleted
    //deletes all childs
    protected static function boot() {
        parent::boot();
        static::deleting(function($invTransactionHeader) { // called BEFORE delete()
            $invTransactionHeader->invTransactionDetails()->delete();
        });
    }
}
