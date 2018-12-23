<?php namespace App\Models;
use CodeIgniter\Model;
class AnalyticsModal extends Model
{
    protected $table      = 'analytics';
    protected $primaryKey = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'code', 'ip', 'time', 'data'];
    protected $useTimestamps = false;
    protected $skipValidation     = false;
}