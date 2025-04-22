<?php

namespace Thunder;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Dailytokes class
 */

class Dailytokes extends Migration
{
	//this is when we create the table
	public function up()
	{

		/** create a table **/
		$this->addColumn('id int(11) NOT NULL AUTO_INCREMENT');
		$this->addColumn('date_drop datetime NOT NULL');
		$this->addColumn('daily_drop_amount DECIMAL(10,2) NOT NULL');
		$this->addColumn('delete_drop_date datetime NULL');
		$this->addPrimaryKey('id');
		/*
		$this->addUniqueKey();
		*/
		$this->createTable('dailytokes');

		/** insert data **/
		//this is just to remind me on how to add to the table. 
		//I don't know if I will need to add data to the table or not.
		//$this->addData('employee_number',900244404);

		//$this->insertData('tokes');
	} 
	//this is when we drop the table
	public function down()
	{
		$this->dropTable('dailytokes');
	}

}