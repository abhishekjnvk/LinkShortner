<?php

namespace App\Controllers;

use App\Models\AnalyticsModal;
use App\Models\LinkModal;

class Track extends BaseController
{
	function _remap($linkCode)
	{
		helper('master_helper');
		$session = session();
		$data = [];

		if ($linkCode) {
			$analytics_modal = new AnalyticsModal();
			$link_modal = new LinkModal();
			$link_detail = $link_modal->where('code', sanitiesString($linkCode))->first();
			$authenticated = false;
			$limit = sanitiesString($this->request->getVar('limit'));
			if (empty($limit)) {
				$limit = 20;
			}
			$page = sanitiesString($this->request->getVar('page'));
			$password = sanitiesString($this->request->getVar('password'));

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
				// count unique country and unique city
				$unique_country = $analytics_modal->where('code', $linkCode)->select('country')->groupBy('country')->countAllResults();
				$unique_city = $analytics_modal->where('code', $linkCode)->select('city')->groupBy('city')->countAllResults();
				$data['total_clicks'] = $analytics_modal->where('code', $linkCode)->select('sum(times) as total_count')->first()->total_count;

				$data['unique_country'] = $unique_country;
				$data['unique_city'] = $unique_city;
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
