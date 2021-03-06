<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descriptor extends Model
{
    //which table to attach to
    protected $table = 'descriptors';
    
    //which field are used for mass assigment
    protected $fillable = [
        'description',
        'descriptor_type_id'
    ];
    public static $rules= array(
        'description' => 'required|unique:descriptors,description,null,{{$id}}'
    );
    
}
