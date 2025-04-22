<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Dailytokes class
 */
class Dailytokes
{
	use MainController;

	public function index()
	{
		
		$data['title'] = 'Daily Tokes';
		$this->view('dailytokes', $data);
	}

}
