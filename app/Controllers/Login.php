<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Cors;

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

    public function logout(){
      if($this->session->get('logged_id')){
          $this->session->destroy();
          $mensaje=['message'=>'Sesión cerrada'];
          return $this->respond($mensaje);
      }
    }
}
