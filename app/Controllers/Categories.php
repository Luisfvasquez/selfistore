<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Filters\Cors;

class Categories extends ResourceController
{
    protected $modelName = 'App\Models\CategoriesModel';
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
       
        $categoriess=$this->model->findAll();
        return $this->respond($categoriess);
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
       
        $categoriess=$this->model->find($id);
        if($categoriess){
            return $this->respond($categoriess);
        }

        return $this->failNotFound('Categoria no encontrada '.$id);
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
     
        if($this->model->insert($data)){            
            $mensaje=['message'=>'Categoria Creada'];
        return  $this->respondCreated([$data,$mensaje],'Categoria creada');
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
       
        $categories=$this->model->find($id);

        if(!$categories){
            return $this->failNotFound('Categoria no encontrada '.$id);
        }
        $data=$this->request->getJSON(true);
        if( $this->model->update($id,$data)){
            return $this->respondUpdated($data,'Categoria actualizada');
        }
      
        return $this->failValidationErrors($this->model->errors());

    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'DELETE, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        
        $categories=$this->model->find($id);

        if($categories){
            $this->model->delete($id);
            return $this->respondDeleted($categories,'Categoria eliminada');
        }

          return $this->failNotFound('Categoria no encontrada '.$id);
    }
}
