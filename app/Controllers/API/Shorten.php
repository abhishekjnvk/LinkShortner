<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\LinkModal;
use CodeIgniter\Config\Services;

class Shorten extends BaseController
{
    public function index()
    {
        $this->response->setContentType('Content-Type: application/json');
        $request = service('request');

        $this->response->setContentType('Content-Type: application/json');
        $method = strtoupper($request->getMethod());
        $throttler = Services::throttler();
        $isLimitRemains = $throttler->check(md5($request->getIPAddress()), 40, MINUTE);

        if (!$isLimitRemains) {
            $this->response->setStatusCode(429);
            $response['status'] = false;
            $response['message'] = "Too Many Requests";
            return json_encode($response);
        }

        if ($method === "POST") {
            $body = $this->request->getJSON();
            if (!isset($body->url) && empty($body->url)) {
                $this->response->setStatusCode(400);
                $response['status'] = false;
                $response['message'] = "URL is required";
            } else {
                $url = $body->url;
                if (!$this->isValidURL($url)) {
                    $this->response->setStatusCode(400);
                    $response['status'] = false;
                    $response['message'] = "Invalid URL";
                    return json_encode($response);
                }
                $analyticsPassword = "";
                $linkTitle = "";
                $expiry = 0;

                if (isset($body->analyticsPassword)) {
                    $analyticsPassword = $body->analyticsPassword;
                }
                if (isset($body->expiry)) {
                    $expiry = $body->expiry;
                }
                if (isset($body->linkTitle)) {
                    $linkTitle = $body->linkTitle;
                }
                $link_model = new LinkModal();

                $link = $link_model->shortenUrl($url, $analyticsPassword, $linkTitle, $expiry);

                if (empty($link)) {
                    $this->response->setStatusCode(500);
                    $response['status'] = false;
                    $response['message'] = "Something went wrong";
                    return json_encode($response);
                } else {
                    $link['link'] = base_url("/go/" . $link['code']);

                    $link = $link_model->normalizeUrl($link);
                    $this->response->setStatusCode(200);
                    $response['message'] = "OK";
                    $response['status'] = true;
                    $response['data'] = $link;

                    return json_encode($response);
                }
            }
        } else {
            $response['message'] = "Method not allowed";
            $this->response->setStatusCode(405);
            $response['status'] = false;
        }
        return json_encode($response);
    }


    private function isValidURL($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}
