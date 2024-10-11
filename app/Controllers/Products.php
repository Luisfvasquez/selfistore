<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Cors;

class Products extends ResourceController
{
    protected $modelName = 'App\Models\ProductsModel';
    protected $format    = 'json'; 
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
       
        $products=$this->model->findAll();
        return $this->respond($products);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');

        $data=$this->model->find($id);
        if($data){
            return $this->respond($data);
        }
        return $this->failNotFound('Producto no encontrado '.$id);
    }

   
    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    
        
        $data=$this->request->getJSON(true);       
         
        if($this->model->insert([           
            'Category_id'=>$data['Category_id'],            
            'Name_product'=>$data['Name_product'],
            'Description'=>$data['Description'],
            'Image'=>$data['Image'],
            'Status'=>$data['Status'],
            'Price'=>$data['Price'],
        ])){
            $mensaje=['message'=>'Producto Creado'];
        return  $this->respondCreated([$data,$mensaje],'Producto creado');
        }
        
        return $this->failValidationErrors($this->model->errors());
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'PUT, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
       
        $product=$this->model->find($id);

        if(!$product){
            return $this->failNotFound('Producto no encontrado '.$id);
        }
        $data=$this->request->getJSON(true);
        if( $this->model->update($id,$data)){
            return $this->respondUpdated($data,'Producto actualizado');
        }
      
        return $this->failValidationErrors($this->model->errors());
    }

   
}
