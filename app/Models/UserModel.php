<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'Cedula';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Cedula','Password','Name_user','Last_name','Phome_number','Rol_id','imagen'];

 
/*     // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
 */
    // Validation
    protected $validationRules      = [
        'Cedula' => 'required|is_unique[users.Cedula]',
        'Password' => 'required',
        'Name_user' => 'required',
        'Last_name' => 'required',
        'Phome_number' => 'required',
        'Rol_id' => 'required|integer',
    ];
  

}
