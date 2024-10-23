<?php

namespace App\Models;

use CodeIgniter\Model;

class BillCkeckoutModel extends Model
{
    protected $table            = 'bill_checkout';
    protected $primaryKey       = 'Bill_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Bill_id','User_id','Capture'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'Bill_id'=>'required|integer',
        'User_id'=>'required|integer',
        'Capture'=>'required|string',
    ];
   
}
