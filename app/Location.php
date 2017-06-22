<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //which table to attach to
    protected $table = 'locations';
    protected $guarded = array('id');
    
    //which field are used for mass assigment
    protected $fillable = [
        'description'
    ];
    
    public static $rules= array(
        'description' => 'required|unique:storages,description,null,{{$id}}'
    );
}
