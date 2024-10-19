<?php

namespace App\Controllers;

use App\Models\ProductsModel;
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
        $data=[            
                "IdImage"=> $images[0]->IdImage,
                "Products_id"=> $images[0]->Products_id,
                "Url"=> $images[0]->Url,
                "Name"=> $images[0]->Name              
        ];

        return $this->respond($data);
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
   
        $id=$this->request->getPost('Id');
        if(empty($_POST['Id'])){
            return $this->fail('Falta el campo Id');
        }

            $file=$this->request->getFile('file1');     

            if(empty($file)){
                return $this->fail('No se ha subido ningun archivo');
            }
           
            if(!$file->isValid()){               
                return $this->fail('No se ha podido subir el archivo');
            }


            if(!$file->hasMoved()){
                $ruta= ROOTPATH.'public/ImageProducts';
                $file->move($ruta, $file->getName(),true);
            }
               
        $url='public/ImageProducts/'.$file->getName();

        $data=[
            'Products_id'=>$id,
            'Url'=>$url
        ];
       $this->model->insert($data);
      
                
            $file=$this->request->getFile('file2');    

            if(empty($file)){
                return $this->fail('No se ha subido ningun archivo');
            }
           
            if (!$file->isValid()) {
                $error = $file->getError();
                switch ($error) {
                    case 4:
                        return $this->fail('No se ha subido ningún archivo');
                    case 1:
                        return $this->fail('El archivo es demasiado grande (UPLOAD_ERR_INI_SIZE)');
                    case 2:
                        return $this->fail('El archivo es demasiado grande (UPLOAD_ERR_FORM_SIZE)');
                    // Agrega otros casos según sea necesario
                    default:
                        return $this->fail("Error al subir el archivo: $error");
                }
            }
            
            // Si el archivo es válido, procede con el procesamiento

            if(!$file->hasMoved()){
                $ruta= ROOTPATH.'public/ImageProducts';
                $file->move($ruta, $file->getName(),true);
            }
               
        $url='public/ImageProducts/'.$file->getName();

        $data=[
            'Products_id'=>$id,
            'Url'=>$url
        ];
       $this->model->insert($data);
      
                
        
        $producModel= new ProductsModel();
        $producModel->update($id,[
            'Image'=> $id,
        ]);

        return $this->respondCreated("Imagenes Subidas",'Imagen subida');       
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

   /*  for($i=1;$i<=5;$i++){
        $archivos= "file" . $i;        
        $file=$this->request->getFile($archivos);     

        if(empty($file)){
            return $this->fail('No se ha subido ningun archivo');
        }
       
        if(!$file->isValid()){               
            return $this->fail('No se ha podido subir el archivo');
        }


        if(!$file->hasMoved()){
            $ruta= ROOTPATH.'public/ImageProducts';
            $file->move($ruta, $file->getName(),true);
        }
           
    $url='public/ImageProducts/'.$file->getName();

    $data=[
        'Products_id'=>$id,
        'Url'=>$url
    ];
   $this->model->insert($data);
  
    }     */    
}
