<?php

namespace Thunder;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Users class
 */
// This is a sample migration file. You can use this as a template to create your own migration files.
//the thunder file is a command line tool that helps you to create and run migrations.
//it can also create controllers, models, and migrations for you.
//this is why Users is used as a placeholder for the class name.
//when you run the command "php thunder make:migration users"
class Users extends Migration
{
	//this is when we create the table
	public function up()
	{

		/** create a table **/
		$this->addColumn('id int(11) NOT NULL AUTO_INCREMENT');
		$this->addColumn('employee_number int(15) NULL');
		$this->addColumn('email varchar(100) NULL');
		$this->addColumn('password varchar(255) NULL');
		$this->addColumn('full_name varchar(255) NULL');
		$this->addColumn('access_granted int(1) NULL DEFAULT 0');// everyone will be 0 by default.
		$this->addColumn('token_hash varchar(64) NULL');
		$this->addColumn('token_expiry datetime NULL');
		$this->addColumn('login_attempts int(11) NULL DEFAULT 0');
		//$this->addColumn('date_updated datetime NULL');
		//I may need to add another column for the temporary hash value
		//this is the column that will be used to store the hash value for the password reset link. I just don't know how I'll do it yet. 
		$this->addPrimaryKey('id');
		$this->addUniqueKey('token_hash');
		$this->addUniqueKey('employee_number');
		$this->addUniqueKey('email');
		/*
		$this->addUniqueKey();
		*/
		$this->createTable('users');

		/** insert data **/
		$this->addData('employee_number',900244404);
		$this->addData('email', 'jimmyuribe4@outlook.com');
		$this->addData('password', password_hash('193782jim', PASSWORD_DEFAULT));
		$this->addData('full_name', 'Jimmy Uribe');
		$this->addData('access_granted', 0);
		$this->addData('login_attempts', 0);
		
		
		//I commented this out because the table I created 
		//doesn't have these columns. I might add a hash column 
		//to allow user to reset password.
		//$this->addData('date_created',date("Y-m-d H:i:s"));
		//$this->addData('date_updated',date("Y-m-d H:i:s"));

		$this->insertData('users');
	} 
	//this is when we drop the table
	public function down()
	{
		$this->dropTable('users');
		//add another line of code like the one below to drop the table
		//$this->dropTable('users2');
	}

}