<?php

namespace Thunder;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Dailytokes class
 */
// This is a sample migration file. You can use this as a template to create your own migration files.
//the thunder file is a command line tool that helps you to create and run migrations.
//it can also create controllers, models, and migrations for you.
//this is why Dailytokes is used as a placeholder for the class name.
//when you run the command "php thunder make:migration dailytokes"
class Dailytokes extends Migration
{
	//this is when we create the table
	public function up()
	{

		/** create a table **/
		$this->addColumn('id int(11) NOT NULL AUTO_INCREMENT');
		$this->addColumn('date_drop datetime NULL');
		$this->addColumn('daily_drop_amount DECIMAL(10,2) NOT NULL');
		$this->addColumn('delete_drop_date datetime NULL');
		$this->addPrimaryKey('id');
		/*
		$this->addUniqueKey();
		*/
		$this->createTable('tokes');

		/** insert data **/
		$this->addData('date_created',date("Y-m-d H:i:s"));
		$this->addData('date_updated',date("Y-m-d H:i:s"));

		$this->insertData('tokes');
	} 
	//this is when we drop the table
	public function down()
	{
		$this->dropTable('dailytokes');
	}

}