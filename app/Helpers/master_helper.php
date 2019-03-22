<?php

use App\Models\AnalyticsModal;
use App\Models\LinkModal;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;

if (!function_exists('getIpData')) {
    function getIpData($ip)
    {
        if (!$details = cache("ipDataOf_$ip")) {
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
            cache()->save("ipDataOf_$ip", $details, 864000);
        }
        return $details;
    }
}
if (!function_exists('getCity')) {
    function getCity($ip)
    {
        $details = getIPData($ip);
        if (empty($details->city)) {
            return "";
        }
        $city = $details->city;
        $region = $details->region;
        $country = $details->country;
        if ($region) {
            if ($country) {
                $city = $city . ", " . $region . " (" . $country . ")";
            } else {
                $city = $city . ", " . $region;
            }
        } else {
            if ($country) {
                $city = $city . "(" . $country . ")";
            }
        }
        return $city;
    }
}
if (!function_exists('getISP')) {
    function getISP($ip)
    {
        $details = getIPData($ip);
        if (empty($details->org)) {
            return "";
        }
        $isp = $details->org;
        return $isp;
    }
}

if (!function_exists('generate_id')) {
    function generate_id()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $id_length=6;
        for ($i = 0; $i < $id_length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $property = new LinkModal();
        if (empty($property->where('code', $randomString)->findAll())) {
            return $randomString;
        } else {
            generate_id();
        }
    }
}
if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}


if (!function_exists('logData')) {
    function logData($id, $userAgent)
    {
        if (isset($id) && isset($userAgent)) {
            helper('master_helper');
            $analytics_modal = new AnalyticsModal();
            AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);
            $dd = new DeviceDetector($userAgent);
            $dd->parse();
            if ($dd->isBot()) {
                // $botInfo = $dd->getBot();
            } else {
                $clientInfo = $dd->getClient();
                $osInfo = $dd->getOs();
                $device = $dd->getDeviceName();
                $brand = $dd->getBrandName();
                $model = $dd->getModel();

                $ip=get_client_ip();
                $analytics_data['clientInfo'] = $clientInfo;
                $analytics_data['osInfo'] = $osInfo;
                $analytics_data['device'] = $device;
                $analytics_data['brand'] = $brand;
                $analytics_data['model'] = $model;
                
                $analytics_database['data'] = json_encode($analytics_data);
                $analytics_database['time'] = time();
                $analytics_database['code'] = $id;
                $analytics_database['ip'] = $ip;
            
                // $pro=$analytics_modal->where(["ip"=>$ip,"code"=>$id])->first();
                // if(empty($pro)){
                    $analytics_modal->save($analytics_database);
                // }else{
                //     $new_data['times']=$pro->times+1;
                //     $analytics_modal->where(["ip"=>$ip,"code"=>$id])->save($new_data);
                // }

            }
        }
    }
}
