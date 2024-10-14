<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Image extends ResourceController
{
    protected $modelName = 'App\Models\ImageModel';
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

        $images = $this->model->findAll();
        return $this->respond($images);
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
       
        $image=$this->model->find($id);
        if($image){
            return $this->respond($image);
        }

        return $this->failNotFound('Imagen no encontrada '.$id);
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

        $file=$this->request->getFile('file');        
       
        if(empty($file)){
            return $this->fail('No se ha subido ningun archivo');
        }
        if(empty($_POST['Id'])){
            return $this->fail('Falta el campo Id');
        }

        if(!$file->isValid()){
           
            return $this->fail('No se ha podido subir el archivo');
        }
        
        if(!$file->hasMoved()){
            $ruta= ROOTPATH.'public/ImageProducts';
            $file->move($ruta, $file->getName(),true);
        }
      
        $id=$this->request->getPost('Id');
        $data=[
            'Products_id'=>$id,
            'Url'=>'public/ImageProducts/',
            'Name'=>$file->getName()
        ];
       
        $this->model->insert($data);
        return $this->respondCreated($data,'Imagen subida');       
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

        $image=$this->model->find($id);

        if(!$image){
            return $this->failNotFound('Imagen no encontrada '.$id);
        }
        $data=$this->request->getJSON(true);
        if( $this->model->update($id,$data)){
            return $this->respondUpdated($data,'Imagen actualizada');
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
        
        $image=$this->model->find($id);

        if($image){
            $this->model->delete($id);
            return $this->respondDeleted($image,'Imagen eliminada');
        }

          return $this->failNotFound('Imagen no encontrada '.$id);
    }
}
