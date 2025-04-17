<?php

namespace Thunder;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * {CLASSNAME} class
 */
// This is a sample migration file. You can use this as a template to create your own migration files.
//the thunder file is a command line tool that helps you to create and run migrations.
//it can also create controllers, models, and migrations for you.
//this is why {CLASSNAME} is used as a placeholder for the class name.
//when you run the command "php thunder make:migration {classname}"
class {CLASSNAME} extends Migration
{
	//this is when we create the table
	public function up()
	{

		/** create a table **/
		$this->addColumn('id int(11) NOT NULL AUTO_INCREMENT');
		$this->addColumn('date_created datetime NULL');
		$this->addColumn('date_updated datetime NULL');
		$this->addPrimaryKey('id');
		/*
		$this->addUniqueKey();
		*/
		$this->createTable('{classname}');

		/** insert data **/
		$this->addData('date_created',date("Y-m-d H:i:s"));
		$this->addData('date_updated',date("Y-m-d H:i:s"));

		$this->insertData('{classname}');
	} 
	//this is when we drop the table
	public function down()
	{
		$this->dropTable('{classname}');
	}

}