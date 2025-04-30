<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Payperiod class
 */
class Payperiod
{
	
	use Model;

	protected $table = 'payperiod';
	protected $primaryKey = 'id';
	protected $loginUniqueColumn = 'start_pp';

	protected $allowedColumns = [

		'start_pp',
		'end_pp',
		'pickup_cost',
		'payroll_adj',
		'poker_other',
		'total_hrs',
		'td_no_adj',
		'toke_rate',
	];

	/*****************************
	 * 	rules include:
		required
		alpha
		email
		numeric
		unique
		symbol
		longer_than_8_chars
		alpha_numeric_symbol
		alpha_numeric
		alpha_symbol
	 * 
	 ****************************/
	//I really don't need to validate anything here
	//since there is no form submitting data to the table
	//but I will leave this here for future reference
	protected $validationRules = [

		'email' => [
			'email',
			'unique',
			'required',
		],
		'username' => [
			'alpha',
			'required',
		],
		'password' => [
			'not_less_than_8_chars',
			'required',
		],
	];

	public function populateDates(){

		$strToday = date('Y-m-d');

		//check if the table has data in the start_pp column
		//if it doesn't then I will populate the table with 
		//the current payperiod and the previous payperiod.
		$query = "SELECT * FROM {$this->table} WHERE {$this->loginUniqueColumn} > '{$strToday}';";
		$result = $this->query($query);
		show($result[0]);

		//this if is for when the result is empty or false. the if($result) is temporary 
		//and I will change it once I figure this out. 
		//if($result == false || empty($result)){
		if($result){
			//show("data not found");
			$query = "SELECT end_pp FROM {$this->table} ORDER BY end_pp DESC LIMIT 1;";
			$result = $this->query($query);
			show($result);

			//comparing dates
			$end_pp = new \DateTime($result[0]->end_pp);
			$today = new \DateTime($strToday);
			show($today);
			$difference = $end_pp->diff($today);
			$difference = $today->diff($end_pp);
			show($difference->days);

			//create a for loop an compare this to the current date. 
			//if the current date is greater than the end_pp then I will 
			//add +14 days to the end_pp and compare it to the current date again.
			//Once the current date is less than the end_pp then I will break the loop.
			//and update the table with the new end_pp and start_pp dates as the 
			//for loop iterates.
			$end_pp->modify('+14 day');
			show($end_pp);

			//I found a way to get the difference between two dates
			//I need to use this info to populate the table with the current payperiod
			//and the previous payperiod. It will depend how long its been since the last
			//payperiod ended. I will use the difference to determine how many payperiods
			//I need to add to the table.

		}
		else {
			echo "this is the else";
		}
	}

	//returns false if the data is not valid
	public function getCurrentPayperiod(): string
	{
		//I need to get the current date;
		date_default_timezone_set('America/New_York');
		$strtoday = date('Y-m-d');
		//I need to get the data with the current date
		$query = "SELECT * FROM {$this->table} WHERE {$this->loginUniqueColumn} > '{$strtoday}';";
		$result = $this->query($query);

		
		//check if the result is valid or empty
		if($result)
		{
			//this converst the desired data to a date object

			if (is_array($result) && isset($result[0]->start_pp)) {
				//creating variable to store the start of the current payperiod
				$Start_of_currentPayperiod = new \DateTime($result[0]->start_pp);
				//modifying the date to get the start of the current payperiod
				$Start_of_currentPayperiod->modify('-14 day');

				//$previousPayperiod = clone $Start_of_currentPayperiod;
				//$previousPayperiod->modify('-14 day');

				
				//show("Start_of_currentPayperiod: ");
				//show($Start_of_currentPayperiod);
				//show($Start_of_currentPayperiod->format('l'));

				//show ("Start_of_previousPayperiod: ");
				//show($previousPayperiod);

			}
			else {
				//show("Invalid or empty result set.");
				return false;
			}
		}
		else
		{
			return false;
		}
		//this return a string of the date in the format of Y-m-d
		return $Start_of_currentPayperiod->format('m/d/Y');
	}
	
	public function getPreviousPayperiod():string
	{
		//I need to get the current date;
		date_default_timezone_set('America/New_York');
		$strtoday = date('Y-m-d');
		//I need to get the data with the current date
		$query = "SELECT * FROM {$this->table} WHERE {$this->loginUniqueColumn} > '{$strtoday}';";
		$result = $this->query($query);

		
		//check if the result is valid or empty
		if($result)
		{
			//this converst the desired data to a date object

			if (is_array($result) && isset($result[0]->start_pp)) {
				//creating variable to store the start of the current payperiod
				$previousPayperiod = new \DateTime($result[0]->start_pp);
				//modifying the date to get the start of the current payperiod
				$previousPayperiod->modify('-28 day');

				//show ("Start_of_previousPayperiod: ");
				//show($previousPayperiod);
				//show($previousPayperiod->format('l'));

			}
			else {
				//show("Invalid or empty result set.");
				return false;
			}
		}
		else
		{
			return false;
		}
		//this return a string of the date in the format of Y-m-d
		return $previousPayperiod->format('m/d/Y');
	}

}