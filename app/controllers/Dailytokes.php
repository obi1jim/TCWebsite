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

		//don't forgot to set the restriction to only 
		//allow users with access to this page
		
		$data['title'] = 'Daily Tokes';
		$payperiod = new \Model\Payperiod;
		$data['start_pp'] = $payperiod->getCurrentPayperiod();
		show($data['start_pp']);
		$this->view('dailytokes', $data);
	}

}
