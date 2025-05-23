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
			$dailytokes = new \Model\Dailytokes;
			
			$payperiod->populateDates();
			$data['ctd_sum'] = $dailytokes->getCurrentTotalDropsSum();
			$data['ptd_sum'] = $dailytokes->getPreviousTotalDropsSum();
			$data['pTokeRate'] = $payperiod->getPreviousTokeRate($data['ptd_sum']);
			$data['estimate_hours'] = $payperiod->getEstimateHours();
			$data['days_drop_count'] = $dailytokes->getNumDaysWithDrops();
			//echo $data['days_drop_count'];
			//echo $data['estimate_hours'];
			// $data['start_pp'] = $payperiod->getCurrentPayperiod();
			// $data['previous_pp'] = $payperiod->getPreviousPayperiod();

			//The strtotime() function converts the date string into a Unix timestamp,
			//which is an integer representing the number of seconds since January 1, 1970 
			//(the Unix epoch). This timestamp is used to perform date and time calculations.
			//$data['dow_start'] = date('l', strtotime($data['start_pp']));
			//$data['dow_previous'] = date('l', strtotime($data['previous_pp']));

			
			$dailytokes->updateDailyDropsTable();
			$data['daily_tokes'] = array_reverse($dailytokes->getDailyDrop());

			
			
			//passing $data in the parameter passses the variables
			//and their stored values to the view.
			$this->view('dailytokes', $data);
		}
		else
		{
			redirect('home');
		}
		
	}

}
