<?php

namespace Thunder;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Dailytokes class
 */

class Dailytokes extends Migration
{

	private $days = ['Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
	//this is when we create the table
	public function up()
	{

		/** create a table **/
		$this->addColumn('id int(11) NOT NULL AUTO_INCREMENT');
		$this->addColumn('day_of_week VARCHAR(10) NOT NULL');
		$this->addColumn('date_drop DATE NULL');
		$this->addColumn('daily_drop DECIMAL(15,2) NULL');
		$this->addColumn('expiry DATE NULL');//this will allow current and previous 
		//payperiods to be in the table. Anything older than that will be deleted. 
		$this->addPrimaryKey('id');
		/*
		$this->addUniqueKey();
		*/
		$this->createTable('dailytokes');
		$this->addUniqueKey('date_drop');

		/** insert data **/
		for($day = 0; $day < count($this->days); $day++)
		{
			$this->addData('day_of_week', $this->days[$day]);
			$this->addData('daily_drop', 0.00);
			$this->insertData('dailytokes');
		}
		for($day = 0; $day < count($this->days); $day++)
		{
			$this->addData('day_of_week', $this->days[$day]);
			$this->addData('daily_drop', 0.00);
			$this->insertData('dailytokes');
		}
		for($day = 0; $day < count($this->days); $day++)
		{
			$this->addData('day_of_week', $this->days[$day]);
			$this->addData('daily_drop', 0.00);
			$this->insertData('dailytokes');
		}
		for($day = 0; $day < count($this->days); $day++)
		{
			$this->addData('day_of_week', $this->days[$day]);
			$this->addData('daily_drop', 0.00);
			$this->insertData('dailytokes');
		}

		
	} 
	//this is when we drop the table
	public function down()
	{
		$this->dropTable('dailytokes');
	}

}