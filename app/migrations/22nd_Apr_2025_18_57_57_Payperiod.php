<?php

namespace Thunder;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Payperiod_numbers class
 */

class Payperiod extends Migration
{
	//this is when we create the table
	public function up()
	{

		/** create a table **/
		$this->addColumn('id int(11) NOT NULL AUTO_INCREMENT');
		$this->addColumn('start_payperiod datetime NOT NULL');
		$this->addColumn('end_payperiod datetime NOT NULL');
		$this->addColumn('adjustments DECIMAL(10,2) NULL');
		$this->addColumn('total_drops DECIMAL(10,2) NULL');
		$this->addColumn('total_hours int(11) NULL');
		$this->addColumn('toke_rate DECIMAL(10,2) NULL');
		$this->addPrimaryKey('id');
		/*
		$this->addUniqueKey();
		*/
		$this->createTable('payperiod');

		/** insert data **/
		

		//$this->insertData('payperiod_numbers');
	} 
	//this is when we drop the table
	public function down()
	{
		$this->dropTable('payperiod');
	}

}