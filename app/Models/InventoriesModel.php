<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoriesModel extends Model
{
    protected $table            = 'inventories';
    protected $primaryKey       = 'Product_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Product_id','Amount_inventory'];

    
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'Product_id'=>'required|integer',
        'Amount_inventory'=>'required|integer',
    ];
    
    public function InventoriesAmount(){
        return $this->select('Amount_inventory')->findAll();
    }
}
