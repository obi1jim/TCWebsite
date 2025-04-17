<?php 
//Make sure you change the ROOT path to the correct one when creating a new project.
//also make sure to change the database credentials to the correct ones.
//APP_NAME and APP_DESC are the name and description of the application.
//DEBUG is used to show errors or not.
//change debug to false when you are done with the project.
defined('ROOTPATH') OR exit('Access Denied!');

if((empty($_SERVER['SERVER_NAME']) && php_sapi_name() == 'cli') || (!empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost'))
{
	/** database config **/
	define('DBNAME', 'tc_db');//Change this to your database name 
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBDRIVER', '');
	
	define('ROOT', 'http://localhost/TCWebsite/public');

	/** mailer config**/

	/*This config file is used to define the constants that are being used in the PHPmailer object*/

	/*This will instruct PHPmailer to connect to the SMTP mail server hosted by Gmail*/
	// Gmail's smtp mail server
	define('MAILHOST', "smtp.gmail.com");

	//  Defining the username of the email that was used to for the smtp gmail
	define('USERNAME', "obi1jim.4@gmail.com");

	// defining the password
	define('MAIL_PASSWORD', "ndjzkmcewocjzhsr");

	// Define the email address that the email is SENT FROM
	define('SEND_FROM', "obi1jim.4@gmail.com");

	//define the name or entity that is sending the email
	define('SEND_FROM_NAME', "HC Toke Committee");

	//Define the reply-to address
	define('REPLY_TO', "obi1jim.4@gmail.com");

	//Define the reply-to name
	define('REPLY_TO_NAME', "HC Toke Committee");

}else
{
	/** database config **/
	define('DBNAME', 'tc_db');//Change this to your database name
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBDRIVER', '');

	define('ROOT', 'https://www.yourwebsite.com');//this is for the live server

}

define('APP_NAME', "Toke Committee Website");
define('APP_DESC', "Best website on the planet");

/** true means show errors **/
define('DEBUG', true);
