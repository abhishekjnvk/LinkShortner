<?php

namespace App\Models;

use CodeIgniter\Model;

class LinkModal extends Model
{
    protected $table      = 'links';
    protected $primaryKey = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'link', 'code', 'timestamp', 'isAnalyticsEnabled', 'password', 'title', 'expiry'];
    protected $useTimestamps = false;
    protected $skipValidation     = false;



    public function isCodeInUse($code)
    {
        $link = $this->select('link')->where('code', $code)->first();
        if (!empty($link)) {
            return true;
        } else {
            return false;
        }
    }

    public function getLinkDetail($code)
    {
        $link = $this->where('code', $code)->first();
        if (!empty($link)) {
            if ($link->expiry > 0) {
                if ($link->expiry > time()) {
                    return $link;
                } else {
                    return null;
                }
            } else {
                return $link;
            }
        } else {
            return null;
        }
    }

    public function isAnalyticsEnabled($code)
    {
        $link = $this->getLink($code);
        if (!empty($link)) {
            return $link->isAnalyticsEnabled;
        } else {
            return false;
        }
    }

    public function isPasswordCorrect($code, $password)
    {
        $link = $this->getLink($code);
        if (!empty($link)) {
            if ($link->password == $password) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function shortenUrl($link, $password, $title, $expiry)
    {
        helper('master_helper');
        $code = generate_id();
        $password = isset($password) ? $password : '';
        $expiry = isset($expiry) ? (int) $expiry : 0;
        $expiry = $expiry > 0 ? time() + $expiry : 0;

        $data['link'] = $link;
        $data['title'] = $title;
        $data['code'] = $code;
        $data['password'] = $password;
        $data['timestamp'] = time();
        $data['expiry'] = $expiry;
        $data['isAnalyticsEnabled'] = !empty($password) ? 1 : 0;

        if ($this->save($data)) {
            return ($data);
        } else {
            return false;
        }
    }


    public function normalizeUrl($url)
    {
        unset($url['password']);
        unset($url['isAnalyticsEnabled']);
        unset($url['code']);
        return $url;
    }
}
