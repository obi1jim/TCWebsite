<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Reset_password class
 */
class Reset_password
{
	use MainController;

	public function index()
	{
		
		$data['title'] = 'Reset Password';
		$data['user'] = new \Model\User;
		$req = new \Core\Request;
		$token = $req->get('token') ?? '';
		$token = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');

		if($req->posted()){
			if($_POST['password'] != $_POST['confirm_password']){
				$data['user']->errors['confirm_password'] = "Password and Confirm Password do not match!";
			}else{
				$data['user']->resetPassword($_POST, $token);
			}
			
		}
		
		$this->view('reset_password', $data);
	}

}
