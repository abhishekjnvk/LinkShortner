<?php

namespace App\Controllers;

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

	public function create()
	{
		$session = session();
		if (isset($_POST['link']) && !empty($_POST['link']) && isset($_POST['title']) && !empty($_POST['title'])) {
			helper('master_helper');

			$link_modal = new LinkModal();
			$code = $link_modal->shortenUrl($_POST['link'], $_POST['password'], $_POST['title'], $_POST['expiry']);
			if (!empty($code)) {
				$session->setFlashdata('link_code', $code);
				$session->setFlashdata('server_message', 'Link Generated With Analytics');
				return redirect()->to(getenv('app.baseURL') . "Home/shorten/" . $code);
			} else {
				$session->setFlashdata('server_message', 'Failed');
			}
		} else {
			$session->setFlashdata('server_message', 'Missing Required Field');
		}
		return redirect()->to(getenv('app.baseURL'));
	}

	public function shorten($code)
	{
		$data['link_code'] = $code;
		$link_modal = new LinkModal();
		$link_detail = $link_modal->getLinkDetail($code);
		if (empty($link_detail)) {
			$data['error'] = 'No Link Found';
			return view('error', $data);
		} else {
			if ($link_detail->isAnalyticsEnabled) {
				$data['session_data'] = 'Link Generated With Analytics';
				$data['analytics'] = $code;
			} else {
				$data['session_data'] = 'Link Generated';
				$data['analytics'] = null;
			}
		}
		return view('shorten', $data);
	}
}
