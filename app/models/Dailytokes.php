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
	//this function will be used to update the daily drops table based on the 
	//current pay period of the Payperiod table. Once the new pay period starts,
	//the daily drops table will be updated with the new dates along with the drops
	//being reset to 0. This will be done by getting the start date of the pay period.
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
		// $Test_previous_pp = clone($previous_pp);
		// $Test_previous_pp->modify('-14 day');
		// $Test_current_pp = clone($current_pp);
		// $Test_current_pp->modify('-14 day');

		
		//$row = $this->first([$this->loginUniqueColumn=> $previous_pp->format('Y-m-d')]);
		$row = $this->findAll();
		if($row === false) {
			throw new \Exception("Failed to retrieve data from the database.");
			die("Failed to retrieve data from the database.");
		}
		//reverse the $row array that way $row[0] will be the first element in the array corresponding to the entry with the 
		//id of 1. This will be the first entry in the table.
		$row = array_reverse($row);
		$empty_row_count = 0;
		if(sizeof($row) !== 28){
			echo "The table is either empty, incomplete, or wrong. <br>";
			//echo "The table has " . sizeof($row) . " rows. and it should have exactly 28 <br>";
			
			die("Notify developer to fix issue. How, u ask? They didn't want to put their information here. Ask table games management who the dev is and they can let them know about the issue.<br>");
			redirect('home');
		}

		//this checks if there are any empty rows or rows with the date_drop column set to 0000-00-00.
		for($i = 0; $i < sizeof($row); $i++)
		{
			//show($row[$i]->date_drop);
			if($row[$i]->date_drop === null || $row[$i]->expiry === null){
				$empty_row_count++;
			}
			else if($row[$i]->date_drop === "0000-00-00" || $row[$i]->expiry === "0000-00-00"){
				$empty_row_count++;
			}else{
				continue;
			}
		}

		//this populates the table if the table is empty.
		if($empty_row_count > 0){
			//getting the current date and the previous date and converting them to Date objects
			
			//$previous_pp = date('Y-m-d', strtotime($dailytokes->getPreviousPayperiod()));
			

			//show("previous pp: " . $previous_pp->format('m/d/Y'));
			//show("Current Pay Period: " );
			//show($current_pp->format('m/d/Y'));
			$end_of_pp = clone $current_pp;
			$end_of_pp->modify('+13 day');
			$dateDrop = clone $previous_pp;
			//show("Previous Pay Period: " . $dateDrop->format('m-d-Y'));
			for($id = 1; $id <= 28; $id++)
			{
				if($id === 15){
					//echo "inside the if statement. this modifies the end of pp<br>";
					$end_of_pp->modify('+14 day');
				}else{
					//echo "inside the else statement. this modifies the end of pp<br>";
				}
				//updating the daily drop for the current pay period
				$this->update($id, [
					'date_drop' => $dateDrop->format('Y-m-d'),
					'expiry' => $end_of_pp->format('Y-m-d'),
				]);
				
				$dateDrop->modify('+1 day');
				//show("Updating date drop: " . $date_drop->format('m-d-Y'));
				
			}
		}

		date_default_timezone_set('America/New_York');
		$strtoday = date('Y-m-d');
		//check the expiry.
		if($row[0]->expiry < $strtoday ){
			$dateDrop = new \DateTime($payperiod->getCurrentPayperiod());
			$end_of_new_pp = clone $dateDrop;
			$end_of_new_pp->modify('+27 day');

			// show("The expiry date is: " . $row[0]->expiry . "<br>");
			// show("The current date is: " . $strtoday . "<br>");
			// show("The current pay period is: " . $Test_current_pp->format('m/d/Y') . "<br>");
			// show("The previous pay period is: " . $Test_previous_pp->format('m/d/Y') . "<br>");
			// $newpp = clone $Test_current_pp;
			// $newpp->modify('+14 day');
			// show("Start of the new pay period is: " . $newpp->format('m/d/Y') . "<br>");
			// show("looking at the row: " . $row[13+1]->date_drop . "<br>");
			// show("row daily drop: " . $row[13+1]->daily_drop . "<br>");
			// show("row expiry: " . $row[13+1]->expiry . "<br>");
			// show("row first element: " . $row[0]->id);

			for($i = 0; $i < 14; $i++)
			{
				//updating the daily drop for the current pay period
				$this->update($row[$i]->id, [
					'date_drop' => $row[14+$i]->date_drop,
					'daily_drop' => $row[14+$i]->daily_drop,
					'expiry' => $row[14+$i]->expiry,
				]);

				//show("update row: " . $row[$i]->id . " with: " . $row[14+$i]->date_drop . ' ' . $row[14+$i]->daily_drop . ' '. $row[14+$i]->expiry);
				
			}
			
			for($i = 14; $i < 28; $i++){

				//here I need to create a date object so I can increament 
				//the day to update the new pay period dates. Don't forget to change 
				//back the current_pp in line 86 and 87
				
				//$row[$i]->date_drop = $payperiod->getCurrentPayperiod();
				
					
				$row[$i]->expiry = $end_of_new_pp->format('Y-m-d');
				
				$row[$i]->daily_drop = 0.00;

				//show("update row## " . $row[$i]->id . " with: " . $dateDrop->format("Y-m-d") . ' ' . $row[$i]->daily_drop . ' '. $row[$i]->expiry);
				
				$this->update($row[$i]->id, [
					'date_drop' => $dateDrop->format("Y-m-d"),
					'daily_drop' => $row[$i]->daily_drop,
					'expiry' => $row[$i]->expiry,
				]);
				$dateDrop->modify('+1 day');
			}


		}
	}

	public function getDailyDrop()
	{
		//$row = $this->first([$this->loginUniqueColumn => $date]);
		$row = $this->findAll();
		if($row === false) {
			throw new \Exception("Failed to retrieve data from the database.");
			die("Failed to retrieve data from the database.");
		}

		return $row;
	}
	public function getCurrentTotalDropsSum(){
		// This will get the sum of the daily drops for the last 14 entries (by id descending, then sum)
		$query = "SELECT SUM(daily_drop) as total_drops FROM (SELECT daily_drop FROM {$this->table} ORDER BY id DESC LIMIT 14) as last14;";
		$result = $this->query($query);
		
		if($result === false) {
			throw new \Exception("Failed to retrieve data from the database.");
			die("Failed to retrieve data from the database.");
		}

		return $result[0]->total_drops;
	}

	public function getPreviousTotalDropsSum(){
		// This will get the sum of the daily drops for the first 14 entries (by id ascending, then sum)
		$query = "SELECT SUM(daily_drop) as total_drops FROM (SELECT daily_drop FROM {$this->table} ORDER BY id ASC LIMIT 14) as first14;";
		$result = $this->query($query);

		if($result === false) {
			throw new \Exception("Failed to retrieve data from the database.");
			die("Failed to retrieve data from the database.");
		}
		//show($result);
		return $result[0]->total_drops;
	}
	public function getNumDaysWithDrops(){
		$row = $this->findAll();
		if($row === false) {
			throw new \Exception("Failed to retrieve data from the database.");
			die("Failed to retrieve data from the database.");
		}
		$counter = 0;
		foreach($row as $r)
		{
			if($r->daily_drop > 0.00){
				$counter++;
			}
		}
		//show("The number of days with drops: " . $counter-14);
		$counter = $counter - 14;
		if($counter <=0){
			$counter = 1;
		}
		return $counter;
	}
	
}