<?php

namespace App\Controllers;

use App\Models\AnalyticsModal;

class Track extends BaseController
{
	function _remap($method)
	{
		$session = session();
		if ($method) {
			$analytics_modal = new AnalyticsModal();
			helper('master_helper');
			$all_data = $analytics_modal->where('code', $method)->orderBY('id DESC')->findAll();
			if (empty($all_data))
				$data['server_message'] = 'No Visitor On Your Link';
			$data['all_data'] = $all_data;

			return view('analytics', $data);
		} else {
			$session->setFlashdata('server_message', 'No Data Found');
			return redirect()->to(getenv('app.baseURL'));
		}
	}
}
