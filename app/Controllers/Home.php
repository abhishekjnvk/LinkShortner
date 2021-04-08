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

	public function decode()
	{
		$analytics = new AnalyticsModal();
		helper('form');
		helper('master_helper');

		//get all
		// $all = $analytics->findAll();

		//get unique ip address from $analytics
		$all = $analytics->select('ip')->distinct()->findAll();



		for ($i = 0; $i < count($all); $i++) {
			$ip = getIpData($all[$i]->ip);
			echo $all[$i]->ip . "<br>";
			print_r($ip);
			$update_data = [
				'country' => $ip->country,
				'region' => $ip->region,
				'city' => $ip->city,
				'continent' => $ip->continent,
				'timezone' => $ip->timezone,
			];

			$analytics->where('ip', $all[$i]->ip)->set($update_data)->update();
		}
	}
}
