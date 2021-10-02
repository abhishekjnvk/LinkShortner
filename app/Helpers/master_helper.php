<?php

use App\Models\AnalyticsModal;
use App\Models\LinkModal;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;

define('DEFAULT_IP_DATA', [
    'country' => "",
    'city' => "",
    'region' => "",
    'continent' => "",
    'timezone' => "",
]);


if (!function_exists('getIpDataFromIpApi')) {
    function getIpDataFromIpApi($ip)
    {
        $ipData = DEFAULT_IP_DATA;
        $details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
        if ($details->status == "fail") {
            return $ipData;
        }
        $ipData['country'] = $details->country;
        $ipData['city'] = $details->city;
        $ipData['region'] = $details->regionName;
        $ipData['timezone'] = $details->timezone;

        return $ipData;
    }
}

if (!function_exists('getIpDataFromIpWhoIs')) {
    function getIpDataFromIpWhoIs($ip)
    {
        $ipData = DEFAULT_IP_DATA;
        $details = json_decode(file_get_contents("https://ipwhois.app/json/{$ip}"));
        if ($details->success == true) {
            $ipData['country'] = $details->country;
            $ipData['city'] = $details->city;
            $ipData['region'] = $details->region;
            $ipData['continent'] = $details->continent;
            $ipData['timezone'] = $details->timezone;
        }
        return $ipData;
    }
}

if (!function_exists('getIpDataFromIpWho')) {
    function getIpDataFromIpWho($ip)
    {
        $ipData = DEFAULT_IP_DATA;
        $details = json_decode(file_get_contents("https://ipwho.is/{$ip}"));
        if ($details->success == true) {
            $ipData['country'] = $details->country;
            $ipData['city'] = $details->city;
            $ipData['region'] = $details->region;
            $ipData['continent'] = $details->continent;
            $ipData['timezone'] = $details->timezone->id;
        }

        return $ipData;
    }
}

if (!function_exists('getIpData')) {
    function getIpData($ip)
    {
        if (!$details = cache("ipDataOf_$ip")) {
            $randomNumber = rand(1, 3);
            switch ($randomNumber) {
                case 1:
                    $details = getIpDataFromIpWho($ip);
                    break;
                case 2:
                    $details = getIpDataFromIpApi($ip);
                    break;
                case 3:
                    $details = getIpDataFromIpWhoIs($ip);
                    break;
                default:
                    $details = getIpDataFromIpWhoIs($ip);
                    break;
            }
            cache()->save("ipDataOf_$ip", $details, 3600 * 24 * 365);
        }

        //convert to object
        $details = (object) $details;
        return $details;
    }
}


if (!function_exists('generate_id')) {
    function generate_id()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $id_length = 6;
        for ($i = 0; $i < $id_length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        $property = new LinkModal();
        if ($property->isCodeInUse($randomString) === false) {
            return $randomString;
        } else {
            return generate_id();
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
    function logData($id, $userAgent, $utmSource, $urlParams)
    {
        if (isset($id) && isset($userAgent)) {
            helper('master_helper');
            $analytics_modal = new AnalyticsModal();
            AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);
            $dd = new DeviceDetector($userAgent);
            $dd->parse();
            if ($dd->isBot()) {
                $botInfo = $dd->getBot();
                $analytics_data['botInfo'] = $botInfo;
                $analytics_database['data'] = json_encode($analytics_data);
                $analytics_database['is_bot'] = true;
            } else {
                $clientInfo = $dd->getClient();
                $osInfo = $dd->getOs();
                $device = $dd->getDeviceName();
                $brand = $dd->getBrandName();
                $model = $dd->getModel();
                $analytics_data['clientInfo'] = $clientInfo;
                $analytics_data['osInfo'] = $osInfo;
                $analytics_data['device'] = $device;
                $analytics_data['brand'] = $brand;
                $analytics_data['model'] = $model;
            }

            $ip = get_client_ip();
            $analytics_database['data'] = json_encode($analytics_data);
            $analytics_database['time'] = time();
            $analytics_database['code'] = $id;
            $analytics_database['ip'] = $ip;
            $analytics_database['utm_source'] = $utmSource;
            $analytics_database['url_params'] = $urlParams;
            $analytics_database['times'] = 1;
            $analytics_database['city'] = getIPData($ip)->city;
            $analytics_database['country'] = getIPData($ip)->country;
            $analytics_database['region'] = getIPData($ip)->region;
            $analytics_database['continent'] = getIPData($ip)->continent;
            $analytics_database['timezone'] = getIPData($ip)->timezone;
            $pro = $analytics_modal->where(["ip" => $ip, "code" => $id])->orderBY('time', 'DESC')->first();

            if (empty($pro)) {
                $analytics_modal->save($analytics_database);
            } else {
                $analytics_database['times'] = $pro->times + 1;
                $analytics_modal->save($analytics_database);
            }
        }
    }
}

if (!function_exists('sanitiesString')) {
    function sanitiesString($inputString)
    {
        $allowedCharsRegex = '/[a-zA-Z0-9-_]/';
        $cleanedString = preg_replace($allowedCharsRegex, '', $inputString);
        return $cleanedString;
    }
}
