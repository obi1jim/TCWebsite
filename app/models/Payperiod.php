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
		'id',
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

	//this function will populate the table with more payperiods
	//dates if they happen to run out. this updates the payperiod table.
	//This will ensure that the table always has the current payperiod so 
	//that the DailyTokes table can be use that data to update the daily drops table.
	public function populateDates(){
		date_default_timezone_set('America/New_York');
		$strToday = date('Y-m-d');

		//check if the table has data in the start_pp column
		//if it doesn't then I will populate the table with 
		//the current payperiod and the previous payperiod.
		$query = "SELECT * FROM {$this->table} WHERE {$this->loginUniqueColumn} > '{$strToday}';";
		$result = $this->query($query);
		//show($result[0]);

		//this if is for when the result is empty or false. the if($result) is temporary 
		//and I will change it once I figure this out. 
		if($result == false || empty($result)){

			/*This shows up when the table doesn't have the next payperiod.
			The current date was compared and the function did 
			not find any start_pp dates that were greater than 
			the current date. This means three things: 1) the table is
			empty <br> 2) the table only has data up to the current payperiod.
			Or 3) the table has data but the start_pp dates are all in the past beyond the the previous payperiod.
			*/

			$query = "SELECT * FROM {$this->table} ORDER BY end_pp DESC LIMIT 1;";
			$result = $this->query($query);
			//show("result (should be the last entry of the end_pp:");
			//show($result);

			$endpp = new \DateTime($result[0]->end_pp);
			//show("endpp:");
			//show($endpp);
			$startpp = clone $endpp;
			//this gets me the start of the payperiod for the last record
			$startpp->modify('-13 day');
			//show("startpp:");
			//show($startpp);
			$today = new \DateTime($strToday);
			//show("today:");
			//show($today);
			//$difference = $today->diff($endpp);
			//show("difference between today and the last record of the end_pp: ".$difference->days);
			

			//create a for loop an compare this to the current date. 
			//if the current date is greater than the end_pp then I will 
			//add +14 days to the end_pp and compare it to the current date again.
			//Once the current date is less than the end_pp then I will break the loop.
			//and update the table with the new end_pp and start_pp dates as the 
			//for loop iterates.

			//this limit exists in case something goes wrong and the loop runs forever.
			$limit = 0;
			//this is so that the id nunber increaes by 1 with each iteration.
			//this is to ensure that the id number is unique and does not repeat and its inserted at the end of the table.
			$incID = $result[0]->id;
			while ($startpp < $today && $limit < 50) {
				$startpp = $startpp->modify('+14 day');
				$endpp = $endpp->modify('+14 day');

				//this insert the new entry at the end of the table
				$this->insert([
					'id' => ++$incID,
					'start_pp' => $startpp->format('Y-m-d'),
					'end_pp' => $endpp->format('Y-m-d'),
					'pickup_cost' => 0.00,
					'payroll_adj' => 0.00,
					'poker_other' => 0.00,
					'total_hrs' => 0.00,
					'td_no_adj' => 0.00,
					'toke_rate' => 0.00,
				]);
				/*show("startpp: ".$startpp->format('Y-m-d'));
				show("endpp: ".$endpp->format('Y-m-d'));
				show("today: ".$today->format('Y-m-d'));
				show("incID: ".$incID);
				show("limit: ".$limit);
				*/

				$limit++;
				
			}
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
	public function getEstimateHours(){
		$currentPayperiod = $this->getCurrentPayperiod();
		$currentPayperiod = date('Y-m-d', strtotime($currentPayperiod));
		
		//show("previous payperiod: " . $currentPayperiod);
		
		//$query = "SELECT * FROM {$this->table} WHERE {$this->loginUniqueColumn} = '{$currentPayperiod}';";
		$query = "SELECT * FROM {$this->table} WHERE {$this->loginUniqueColumn} < (SELECT {$this->loginUniqueColumn} FROM {$this->table} WHERE {$this->loginUniqueColumn} = '{$currentPayperiod}') ORDER BY {$this->loginUniqueColumn} DESC LIMIT 5;"; 

		$result = $this->query($query);

		if($result == false || empty($result))
		{
			show("No data found");
			return false;
		}
		//show($result);
		$sum = 0;
		foreach($result as $row)
		{
			
			$sum += $row->total_hrs;
			
			
		}
		//show("sum: " . $sum);
		$average = $sum / count($result);
		//show("average range: " . $average*.85 . " - " . $average*1.15);

		return $average;
		
	}
	public function getPreviousTokeRate($totalDrops = 0.00)
	{
		$p_payperiod = $this->getPreviousPayperiod();
		//I needed to convert this into the format of Y-m-d since that is how 
		//the database store the date.
		$p_payperiod = date('Y-m-d', strtotime($p_payperiod));
		$row = $this->where([$this->loginUniqueColumn => $p_payperiod]);
		
		
		//show($row[0]->toke_rate);
		if($row)
		{
			//show("previous toke rate: " . $row[0]->toke_rate);
			if($row[0]->toke_rate == 0.00)
			{
				$estimateLow = number_format($totalDrops / ($this->getEstimateHours() * 1.04), 2);
				$estimateHigh = number_format($totalDrops / ($this->getEstimateHours() * 0.96), 2);
				$estimateTR = "Estimate: $" . $estimateLow . " - $" . $estimateHigh;
				return $estimateTR;

			}else{
				return $row[0]->toke_rate;
			}
			
		}else{
			return "No Toke Rate Found";
		}
	}
}