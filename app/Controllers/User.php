<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Cors;

class User extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json'; 
    private $contador = 0;
    
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
       
        $users=$this->model->findAll();
        return $this->respond($users);
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
       
        $users=$this->model->find($id);
        if($users){
            return $this->respond($users);
        }

        return $this->failNotFound('Usuario no encontrado '.$id);
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
       
        $requiredFields = [
            'Id' => 'El campo Id requerido',
            'Password' => 'El campo Password requerido',
            'Name_user' => 'El campo Name_user requerido',
            'Email' => 'El campo Email requerido',
            'Phome_number' => 'El campo Phome_number requerido'
        ];
        
        foreach ($requiredFields as $field => $errorMessage) {
            if (empty($data[$field])) {
                return $this->respond($errorMessage);
            }
        }
       
        if($this->model->insert([
            'Id'=>$data['Id'],
            'Password'=>password_hash($data['Password'],PASSWORD_DEFAULT),
            'Name_user'=>$data['Name_user'],
            'Last_name'=>$data['Last_name'],
            'Phome_number'=>$data['Phome_number'],
            'Email'=>$data['Email'],
            'Rol_id'=>"2",
            'token_login'=>null,
            'token_exp'=>null            
        ])){            
            $mensaje=['message'=>'Usuario Creado'];
        return  $this->respondCreated([$data,$mensaje],'Usuario creado');
        }
        return $this->failValidationErrors($this->model->errors());
      
        /* 
         if (empty($data['Password'])) {
            return $this->respond('Password requerido');
        }
        try {

            if ($this->model->insert([
                'Id' => $data['Id'],
                'Password' => password_hash($data['Password'], PASSWORD_DEFAULT),
                'Name_user' => $data['Name_user'],
                'Last_name' => $data['Last_name'],
                'Phome_number' => $data['Phome_number'],
                'Email' => $data['Email'],
                'Rol_id' => "2"
            ])) {
                $mensaje = ['message' => 'Usuario Creado'];
                return  $this->respondCreated([$data, $mensaje], 'Usuario creado');
            }
            return $this->failValidationErrors($this->model->errors());
        } catch (\Exception $e) {
            return $this->failServerError('Error en el servidor');
        }
        */
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
       
        $user=$this->model->find($id);

        if(!$user){
            return $this->failNotFound('Usuario no encontrado '.$id);
        }
        $data=$this->request->getJSON(true);
        if( $this->model->update($id,$data)){
            return $this->respondUpdated($data,'Usuario actualizado');
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
        
        $user=$this->model->find($id);

        if($user){
            $this->model->delete($id);
            return $this->respondDeleted($user,'Usuario eliminado');
        }

          return $this->failNotFound('Usuario no encontrado '.$id);
    }

}
