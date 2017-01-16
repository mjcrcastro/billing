<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescriptorType extends Model
{
    //which table to attach to
    protected $table = 'descriptor_types';
    protected $guarded = array('id');
    
    //which field are used for mass assigment
    protected $fillable = [
        'description'
    ];
    
    public static $rules= array(
        'description' => 'required|unique:descriptor_types,description,null,{{$id}}'
    );
}
