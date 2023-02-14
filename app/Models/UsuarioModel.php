<?php
namespace App\Models;
use CodeIgniter\Model;
//use App\Models\UsuarioModel;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['cedula', 'clave'];
}