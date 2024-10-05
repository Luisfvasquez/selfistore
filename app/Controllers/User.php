<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json'; 
    
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
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
        $data=$this->request->getJSON(true);

        
        if($this->model->insert([
            'Id'=>$data['Id'],
            'Password'=>password_hash($data['Password'],PASSWORD_DEFAULT),
            'Name_user'=>$data['Name_user'],
            'Last_name'=>$data['Last_name'],
            'Phome_number'=>$data['Phome_number'],
            'Rol_id'=>"2"            
        ])){
            $mensaje=['message'=>'Usuario creado'];
        return  $this->respondCreated([$data,$mensaje],'Usuario creado');
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
        $user=$this->model->find($id);

        if($user){
            $this->model->delete($id);
            return $this->respondDeleted($user,'Usuario eliminado');
        }

          return $this->failNotFound('Usuario no encontrado '.$id);
    }

}
