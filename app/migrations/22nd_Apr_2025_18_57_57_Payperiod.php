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
		$this->addColumn('id int(20) NOT NULL AUTO_INCREMENT');
		$this->addColumn('start_pp DATE NOT NULL');
		$this->addColumn('end_pp DATE NOT NULL');
		$this->addColumn('pickup_cost DECIMAL(10,2) NULL');
		$this->addColumn('payroll_adj DECIMAL(10,2) NULL');
		$this->addColumn('poker_other DECIMAL(10,2) NULL');
		$this->addColumn('total_hrs DECIMAL(10,2) NULL');
		$this->addColumn('td_no_adj DECIMAL(15,2) NULL');
		$this->addColumn('toke_rate DECIMAL(10,2) NULL');
		$this->addPrimaryKey('id');
		/*
		$this->addUniqueKey();
		*/
		$this->createTable('payperiod');
		$this->addUniqueKey('start_pp');
		$this->addUniqueKey('end_pp');

		/** insert data **/
		

		//$this->insertData('payperiod_numbers');
	} 
	//this is when we drop the table
	public function down()
	{
		$this->dropTable('payperiod');
	}

}