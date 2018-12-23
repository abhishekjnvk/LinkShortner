<?php namespace App\Models;
use CodeIgniter\Model;
class LinkModal extends Model
{
    protected $table      = 'links';
    protected $primaryKey = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'link', 'code', 'date', 'analytics', 'password', 'title'];
    protected $useTimestamps = false;
    protected $skipValidation     = false;
}