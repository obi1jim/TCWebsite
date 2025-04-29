<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Tokes class
 */
class Dailytokes extends Payperiod
{
	
	use Model;

	protected $table = 'dailytokes';
	protected $primaryKey = 'id';
	protected $loginUniqueColumn = 'date_drop';

	protected $allowedColumns = [

		'day_of_week',
		'date_drop',
		'daily_drop',
		'expiry',
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
	//I may not use this to validate anything since I will only 
	//be deleting item from the table on the website.
	//All the data will be entered using mysql workbench. 
	protected $validationRules = [

		'date_drop' => [
			'unique',
			'required',
		],
		'amount' => [
			'numeric',
			'required',
		],
		
	];

	public function deleteExpiredDates($data)
	{
		// this might be useful later $this->delete($this->table, $this->primaryKey, $date);
		//I am just using the loginUniqueColumn to delete the expired dates.
		//it really isn't a login column because I am not using it for that.
		//I am just using it to delete the expired dates.
		$row = $this->first([
			'delete_date_drop' => $this->loginUniqueColumn,
		]);

		//I need to make sure the date of the current pay period is known. I need to work on that first.


	}
	//This will be called everytime the user logs in to the system. 
	public function updateDailyDropsTable(){
		
		$dailytokes = new Dailytokes;
		
		//getting the current date and the previous date and converting them to Date objects
		$current_pp= date('Y-m-d', strtotime($dailytokes->getCurrentPayperiod()));
		$previous_pp = date('Y-m-d', strtotime($dailytokes->getPreviousPayperiod()));


		$this->update($this->table, $this->primaryKey, $current_date, [
			'date_drop' => $current_date,
			'expiry' => $previous_date,
			'daily_drop' => 0.00,
			
		]);
	}

}