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
		if (isset($_POST['link'])) {
			helper('master_helper');
			$code = generate_id();
			$link_modal = new LinkModal();
			$data['link'] = $_POST['link'];
			$data['code'] = $code;
			$data['date'] = time();
			$data['analytics'] = 1;
			$data['password'] = 1234;
			$data['title'] = "This Is Test";
			if ($link_modal->save($data)) {
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
		if ((empty($link_modal->where('code', $code)->first()))) {
			$data['error'] = 'No Link Found';
			return view('error', $data);
		}
		$data['session_data'] = 'Link Generated With Analytics';
		return view('shorten', $data);
	}
}
