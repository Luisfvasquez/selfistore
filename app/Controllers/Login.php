<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;


class Login extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';
    protected $session;
    
    public function __construct()
    {
        $this->session=\Config\Services::session();
    }

      /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function auth()
    {
        $data=$this->request->getJSON(true);

        $requiredFields = [
            'Id' => 'El campo Id requerido',
            'Password' => 'El campo Password requerido'
        ];
        
        foreach ($requiredFields as $field => $errorMessage) {
            if (empty($data[$field])) {
                return $this->respond($errorMessage);
            }
        }


        $rules=[
            'Id'=>'required',
            'Password'=>'required'
        ];
        
        if(!$this->validate($rules)){
            $mensaje=['Errors'=>'Ambos campos son requeridos'];
            return  $this->respond($mensaje);
        }
        
        $userModel = new UserModel();

        $user=$userModel->login($data['Id']);  


            if(password_verify($data['Password'],$user[0]->Password)){
                /* $mensaje=['message'=>'Usuario autenticado'];
                $user['message']=$mensaje; */

                $data=[
                    'logged_id'=>true,
                    'Id'=>$user[0]->Id,
                    'Name_user'=>$user[0]->Name_user,
                    'Last_name'=>$user[0]->Last_name,
                    'Phome_number'=>$user[0]->Phome_number,
                    'Rol_id'=>$user[0]->Rol_id
                ];
                
               $this->session->set($data);
                return $this->respond($data);
            }

            $mensaje=['Errors'=>'Usuario o contraseña incorrectos'];

            return $this->respond($mensaje);              
    }

    public function logout()
{
    // Obtener el ID del usuario desde la solicitud
    $data = $this->request->getJSON(true);
    
    // Verificar que el ID del usuario esté presente
    if (empty($data['Id'])) {
        return $this->respond(['Errors' => 'El campo Id es requerido']);
    }

    // Comprobar si hay una sesión activa
    if ($this->session->get('logged_id')) {
        // Verificar si el ID de la sesión coincide con el ID del usuario que se quiere desconectar
        if ($this->session->get('Id') == $data['Id']) {
            // Destruir la sesión
            $this->session->destroy();
            $mensaje = ['message' => 'Sesión cerrada'];
            return $this->respond($mensaje);
        } else {
            return $this->respond(['Errors' => 'No se puede cerrar sesión para este usuario']);
        }
    } else {
        return $this->respond(['Errors' => 'No hay sesión activa']);
    }
}
}
