<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentmehodBillModel extends Model
{
    protected $table            = 'paymentmethod_bill';
    protected $primaryKey       = 'Bill_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Bill_id','MethodPay_id','Total_amount'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'Bill_id' => 'required',
        'MethodPay_id' => 'required',
        'Total_amount' => 'required'
    ];
  
}
