<?php

namespace App\Controllers;

use App\Models\AnalyticsModal;
use App\Models\LinkModal;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;

class Go extends BaseController
{
	function _remap($linkCode)
	{
		$session = session();
		helper('master_helper');

		if ($linkCode) {
			$link_modal = new LinkModal();
			$link_detail = $link_modal->where('code', $linkCode)->first();
			if (!empty($link_detail)) {
				$userAgent = $_SERVER['HTTP_USER_AGENT'];
				logData($linkCode, $userAgent);
				$data['link_detail'] = $link_detail;
				$auth_url = $link_detail->link;
				header("Location: $auth_url");
				exit;
			} else {
				$data['error'] = 'No Link Found';
				return view('error', $data);
			}
		} else {
			$data['error'] = 'No Link Found';
			return view('error', $data);
		}
	}
}
