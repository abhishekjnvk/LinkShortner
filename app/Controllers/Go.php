<?php

namespace App\Controllers;

use App\Models\LinkModal;

class Go extends BaseController
{
	function _remap($linkCode)
	{
		helper('master_helper');
		if ($linkCode) {
			$link_modal = new LinkModal();
			$link_detail = $link_modal->getLinkDetail($linkCode);
			if (!empty($link_detail)) {
				if ($link_detail->isAnalyticsEnabled) {
					$userAgent = $_SERVER['HTTP_USER_AGENT'];
					logData($linkCode, $userAgent);
				}
				$auth_url = $link_detail->link;
				echo $auth_url;
				// header("Location: $auth_url");
				exit;
			} else {
				$data['error'] = 'No Link Found/Link Expired';
				return view('error', $data);
			}
		} else {
			$data['error'] = 'No Link Found';
			return view('error', $data);
		}
	}
}
