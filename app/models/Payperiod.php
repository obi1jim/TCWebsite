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
				
				//show("Start_of_currentPayperiod: ");
				//show($Start_of_currentPayperiod);
			}
			else {
				show("Invalid or empty result set.");
				return false;
			}
		}
		else
		{
			return false;
		}
		//this return a string of the date in the format of Y-m-d
		return $Start_of_currentPayperiod->format('Y-m-d');
	}
	
	public function getPreviousPayperiod():string
	{
		

		return "hello";
	}

}