<?php

namespace App\Controllers;

use App\Models\AnalyticsModal;
use App\Models\LinkModal;

class Home extends BaseController
{
	public function index()
	{
		$session = session();
		$data['link_code'] = $session->getFlashdata('link_code');
		$data['session_data'] = $session->getFlashdata('server_message');
		return view('index', $data);
	}
}
