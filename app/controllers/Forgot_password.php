<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Forgot_password class
 */
class Forgot_password
{
	use MainController;

	public function index()
	{
		$session = new \Core\Session;
		if($session->is_logged_in()){
			$data['title'] = 'Change Password';
		}else{
			$data['title'] = 'Forgot Password';
		}
		$data['user'] = new \Model\User;
		$req = new \Core\Request;
		if($req->posted())
		{
			//show($_POST);
			$data['user']->forgotPassword($_POST);
			//show($data['user']->errors);
		}
		$this->view('forgot_password', $data);
	}

}
