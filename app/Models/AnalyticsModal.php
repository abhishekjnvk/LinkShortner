<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsModal extends Model
{
    protected $table      = 'analytics';
    protected $primaryKey = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'code', 'is_bot', 'ip', 'time', 'data', 'times', 'utm_source', 'url_params', 'country', 'city', 'region', 'timezone', 'continent'];
    protected $useTimestamps = false;
    protected $skipValidation     = false;
}
