<?php

namespace App\Controllers;

use App\Models\ImageModel;
use App\Models\InventoriesModel;
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

        $data = $this->model->ProductsImage();
        $inventarioModel = new InventoriesModel();

        $inventario = $inventarioModel->InventoriesAmount();
        
        $i=0;    
        foreach($data as $datos){    
           
            foreach($inventario as $inventarios){              
                    $cantidadNow[] = $inventarios->Amount_inventory;
            }  
            
            $products[]=[
                'IdProduct'=> $datos->IdProduct,
                'Category_id' => $datos->Category_id,
                'Name_product' => $datos->Name_product,
                'Description' => $datos->Description,
                'Status' => $datos->Status,
                'Price' => $datos->Price,
                'Image'=> $datos->imagen_url,
                'Amount_inventory' => $cantidadNow[$i]
               ];           
               $i++;
        }
        
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

        

        $products = $this->model->ProductsImageShow($id);

        $inventarioModel = new InventoriesModel();
        $inventario = $inventarioModel->InventoriesAmountid($id);
        $data=[
            'IdProduct'=> $products[0]->IdProduct,
            'Category_id' => $products[0]->Category_id,
            'Name_product' => $products[0]->Name_product,
            'Description' => $products[0]->Description,
            'Status' => $products[0]->Status,
            'Price' => $products[0]->Price,
            'Image'=> $products[0]->imagen_url,
            'Amount_inventory' => $inventario[0]->Amount_inventory  
           ];

        $image=$this->model->ProductsImageById($id);
        
        $productsRelation=$this->model->ProductsRelation($data['Category_id'],$id);
        if ($productsRelation) {
            return $this->respond([$data,$image,$productsRelation]);
        } 
        if ($data) {
            return $this->respond([$data,$image]);
        }
        return $this->failNotFound('Producto no encontrado ' . $id);
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

        $inventarioModel = new InventoriesModel();
        $data = $this->request->getJSON(true);

        $requiredFields = [
            'Category_id' => 'El campo Category_id requerido',
            'Name_product' => 'El campo Name_product requerido',
            'Description' => 'El campo Description requerido',
            'Amount_inventory' => 'El campo Description requerido',
            'Status' => 'El campo Status requerido',
            'Price' => 'El campo Price requerido'
        ];
        
        foreach ($requiredFields as $field => $errorMessage) {
            if (empty($data[$field])) {
                return $this->respond($errorMessage);
            }
        }

        
        if ($this->model->insert([
            'Category_id' => $data['Category_id'],
            'Name_product' => $data['Name_product'],
            'Description' => $data['Description'],
            'Image' => $data['Image'],
            'Status' => $data['Status'],
            'Price' => $data['Price']
        ])) {
            $idproducto = $this->model->insertID();
            
            if ($inventarioModel->insert([
                'Product_id' => $idproducto,
                'Amount_inventory' => $data['Amount_inventory']
            ])) {
                $mensaje = ['message' => 'Producto Creado'];
                return  $this->respondCreated([$data, $mensaje], 'Producto creado');
            }
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

        $product = $this->model->find($id);

        if (!$product) {
            return $this->failNotFound('Producto no encontrado ' . $id);
        }
        $data = $this->request->getJSON(true);
        if ($this->model->update($id, $data)) {
            return $this->respondUpdated($data, 'Producto actualizado');
        }

        return $this->failValidationErrors($this->model->errors());
    }

}
