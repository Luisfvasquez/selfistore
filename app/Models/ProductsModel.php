<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'IdProduct';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Category_id','Name_product','Description','Image','Status','Price'];
    
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [       
        'Category_id'=>'required|integer',
        'Name_product'=>'required|string',
        'Description'=>'required',
        'Status'=>'required|integer',
        'Price'=>'required|decimal',
    ];  
    public function ProductsImage(){
        return $this->select('products.*, MAX(image.url) as imagen_url')
        ->join('image', 'products.IdProduct = image.Products_id', 'LEFT')
        ->groupBy('products.IdProduct')
        ->findAll();
    }

    public function ProductsImageById($id){
        return $this->select('image.url as Images')
        ->join('image', 'products.IdProduct = image.Products_id', 'LEFT')
        ->where('products.IdProduct', $id)
        ->findAll();
    }

}
