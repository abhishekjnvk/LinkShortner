<?php

namespace App\Controllers;

use App\Models\AnalyticsModal;
use App\Models\LinkModal;

class Track extends BaseController
{
	function _remap($method)
	{
		$session = session();
		if ($method) {
			$analytics_modal = new AnalyticsModal();
			helper('master_helper');
			$link_modal = new LinkModal();
			$link_detail = $link_modal->where('code', $method)->first();
			$fetch = 0;
			if (!empty($link_detail)) {
				if (!empty($link_detail->password)) {
					if (isset($_POST['password'])) {
						if ($link_detail->password == $_POST['password']) {
							$fetch = 1;
						} else {
							$data['server_message'] = 'Wrong Password';
							$data['ask_password'] = 1;
						}
					} else {
						$data['server_message'] = 'Password Required';
						$data['ask_password'] = 1;
					}
				} else {
					$fetch = 1;
				}
			} else {
				$data['server_message'] = 'Invalid Link';
			}
			if ($fetch) {
				$all_data = $analytics_modal->where('code', $method)->orderBY('id DESC')->findAll();
				$data['all_data'] = $all_data;
				if (empty($all_data))
					$data['server_message'] = 'No Visitor On Your Link';
			}

			return view('analytics', $data);
		} else {
			$session->setFlashdata('server_message', 'No Data Found');
			return redirect()->to(getenv('app.baseURL'));
		}
	}
}
