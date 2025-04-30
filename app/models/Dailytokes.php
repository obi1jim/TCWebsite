<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Tokes class
 */
class Dailytokes
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


	}
	//This will be called everytime the user logs in to the system. 
	public function updateDailyDropsTable(){
		
		$payperiod = new Payperiod;
		
		$current_pp = $payperiod->getCurrentPayperiod();
		$previous_pp = $payperiod->getPreviousPayperiod();
		if ($previous_pp === false) {
			throw new \Exception("Failed to retrieve the previous pay period.");
			die("Failed to retrieve the previous pay period.");
		}
		$previous_pp = new \DateTime($previous_pp);
		$current_pp = new \DateTime($current_pp);
		
		$row = $this->first([$this->loginUniqueColumn=> $previous_pp->format('Y-m-d')]);

		if(empty($row)){
			//getting the current date and the previous date and converting them to Date objects
			
			//$previous_pp = date('Y-m-d', strtotime($dailytokes->getPreviousPayperiod()));
			

			//show("previous pp: " . $previous_pp->format('m/d/Y'));
			//show("Current Pay Period: " );
			//show($current_pp->format('m/d/Y'));
			
			$dateDrop = clone $previous_pp;
			//show("Previous Pay Period: " . $dateDrop->format('m-d-Y'));
			for($id = 1; $id <= 28; $id++)
			{
				//updating the daily drop for the current pay period
				$this->update($id, [
					'date_drop' => $dateDrop->format('Y-m-d'),
				]);
				
				$dateDrop->modify('+1 day');
				//show("Updating date drop: " . $date_drop->format('m-d-Y'));
				
			}
		}
		else{
			show("nothing yet");
			
		}
	}
}