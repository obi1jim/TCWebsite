<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * signup class
 */
class Signup
{
	use MainController;

	public function index()
	{
		$data['title'] = 'Signup';
		$data['user'] = new \Model\User;
		$req = new \Core\Request;
		if($req->posted())
		{
			
			$data['user']->signup($_POST);
			//show($data['user']->findAll());
			//show($data['user']->errors);
		}

		$this->view('signup',$data);
	}

}
