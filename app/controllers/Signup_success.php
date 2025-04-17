<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Signup_success class
 */
class Signup_success
{
	use MainController;

	public function index()
	{
		
		$data['title'] = 'Signup Success';
		
		$this->view('signup_success', $data);
	}

}
