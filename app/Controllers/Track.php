<?php

namespace App\Controllers;

use App\Models\AnalyticsModal;
use App\Models\LinkModal;

class Track extends BaseController
{
	function _remap($linkCode)
	{
		$session = session();
		$data = [];

		if ($linkCode) {
			$analytics_modal = new AnalyticsModal();
			helper('master_helper');
			$link_modal = new LinkModal();
			$link_detail = $link_modal->where('code', $linkCode)->first();
			$authenticated = false;
			$limit = 20;
			$page = $this->request->getVar('page');
			$password = $this->request->getVar('password');
			if (empty($page)) {
				$page = 1;
			}
			if (empty($password)) {
				$password = $session->get('password');
			}

			if (!empty($link_detail)) {
				if (!empty($link_detail->password)) {
					if (!empty($password)) {
						if ($link_detail->password == $password) {
							$authenticated = true;
							$session_data = [
								'password' => $link_detail->password,
								'authenticated' => true
							];
							$session->set($session_data);
						} else {
							$data['server_message'] = 'Wrong Password';
							$data['ask_password'] = 1;
						}
					}
				} else {
					$authenticated = true;
				}
			} else {
				$data['server_message'] = 'Invalid Link';
			}



			if ($authenticated) {
				$all_data = $analytics_modal->where('code', $linkCode)->orderBy('id', 'DESC')->paginate($limit, 'analytics_result', $page);
				$data['all_data'] = $all_data;
				$data['pager'] = $analytics_modal->pager;
				$data['total'] = $analytics_modal->where('code', $linkCode)->countAllResults();
				$data['page'] = $page;
				$data['perPage'] = $limit;
				if (empty($all_data))
					$data['server_message'] = 'No Visitor On Your Link';
			}

			$data['authenticated'] = $authenticated;
			return view('analytics', $data);
		} else {
			$session->setFlashdata('server_message', 'No Data Found');
			return redirect()->to(getenv('app.baseURL'));
		}
	}
}
