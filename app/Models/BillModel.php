<?php

namespace App\Models;

use CodeIgniter\Model;

class BillModel extends Model
{
    protected $table            = 'bill';
    protected $primaryKey       = 'IdBill';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['User_id','Date','reference'];

 
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'User_id' => 'required',
        'Date' => 'required',
        'reference' => 'required'
    ];
  
}
