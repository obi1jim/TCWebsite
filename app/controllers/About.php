<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * About class
 */
class About
{
	use MainController;

	public function index()
	{
		$data['title'] = 'About Us';
		$ses = new \Core\Session;
		if($ses->is_logged_in())
		{
			$this->view('about');
		}
		else
		{
			redirect('home');
		}
	}

}
