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
				$redirect_url = $link_detail->link;
				$data['redirect_url'] = $redirect_url;
				header("Location: $redirect_url");
				exit;
				// return view('go', $data);
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
