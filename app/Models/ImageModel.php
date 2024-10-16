<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model
{
    protected $table            = 'image';
    protected $primaryKey       = 'IdImage';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Products_id','Url'];


    // Validation
    protected $validationRules= [
        'Products_id'=>'required',
        'Url'=>'required',
    ];
    
}
