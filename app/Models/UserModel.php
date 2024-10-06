<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'Id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Id','Password','Name_user','Last_name','Phome_number','Email','Rol_id'];        

 
   // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'Created_at';
   
    // Validation
    protected $validationRules      = [
        'Id' => 'required|is_unique[users.Id]|integer|max_length[10]',
        'Password' => 'required|min_length[8]',
        'Name_user' => 'required|max_length[30]',
        'Last_name' => 'required|max_length[30]',
        'Phome_number' => 'required|max_length[15]',
        'Email' => 'required|valid_email',
        'Rol_id' => 'required|integer'
    ];
  
    public function login($id){
        // Consulta a la base de datos de los Usuarios
        return $this->select('*')->where('Id',$id)->findAll();        
    }
}
