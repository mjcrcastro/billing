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
        'descriptorType_id'
    ];
}
