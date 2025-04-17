<?php

namespace Thunder;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Posts class
 */
class Posts extends Migration
{
	
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
		$this->createTable('posts');

		/** insert data **/
		$this->addData('date_created',date("Y-m-d H:i:s"));
		$this->addData('date_updated',date("Y-m-d H:i:s"));
		
		$this->insertData('posts');
	} 

	public function down()
	{
		$this->dropTable('posts');
	}

}