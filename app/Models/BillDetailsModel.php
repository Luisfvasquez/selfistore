<?php

namespace App\Models;

use CodeIgniter\Model;

class BillDetailsModel extends Model
{
    protected $table            = 'bill_details';
    protected $primaryKey       = 'Bill_Id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Bill_Id','Product_Id','Amount_product','Price_unitary'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'Bill_Id' => 'required',
        'Product_Id' => 'required',
        'Amount_product' => 'required',
        'Price_unitary' => 'required'
    ];
  
}
