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
		$ses = new \Core\Session;
		if($ses->is_logged_in())
		{
			//don't forgot to set the restriction to only 
			//allow users with access to this page
			$data['title'] = 'Daily Tokes';
			$payperiod = new \Model\Payperiod;
			$data['start_pp'] = $payperiod->getCurrentPayperiod();
			
			$this->view('dailytokes');
		}
		else
		{
			redirect('home');
		}
		
	}

}
