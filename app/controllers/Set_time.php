<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Set_time class
 */
class Set_time
{
	use MainController;
	/**
	 * I created this controller because 
	 * I wanted to test the session time out.
	 * The session time out starts here when
	 * the user logs in. 
	 */
	public function index()
	{
		
		$data['title'] = 'Redirecting...';
		$ses = new \Core\Session;
		if($ses->is_logged_in()){
			$lastActivity = $ses->get('last_activity');
			echo $lastActivity;
			if(!isset($lastActivity)){
				
				redirect('home');
			}else{
				
				$ses->set('last_activity', time());
			}
		}

		redirect('home');
	}

}
