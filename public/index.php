<?php 

session_start();

/**  Valid PHP Version? **/
$minPHPVersion = '8.0';
if (phpversion() < $minPHPVersion)
{
	die("Your PHP version must be {$minPHPVersion} or higher to run this app. Your current version is " . phpversion());
}

/**  Path to this file **/
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);

require "../app/core/init.php";

DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

$app = new App;
$app->loadController();

/**
 * you just learned what bootstrap is and I am starting 
 * to understand how to use this framework with bootstrap.
 * I am currently working on the home page. I am choosing the 
 * navbar so far. I am creating the html file in segments. 
 * I will create the header and footer files and call them
 * in the home page using the same method as the controllers.
 * 
 * 3/26/2025
 * I am testing the mirgration feature of the framework. 
 * I can create migrations where it stores the database 
 * schema in a file. I can also run the migrations to
 * create the tables in the database. In order to use the 
 * Thunder file, I need to open the terminal and run the
 * command "php thunder" in the root directory of the project.
 * This will display the commands that I can use with the
 * Thunder file. I can create a migration file by running the
 * command "php thunder make:migration {migration name}"
 * I need to make sure I customize the thunder folder files to 
 * match the specific project I am working on.
 * 
 * 3/27/2025
 * I just finished using the thunder file to create a migration file
 * and run the migration to create the users table.
 * I need to make sure that I can login with my creditials.
 * I also need to make sure that I can 
 * create a new user and login with that user.
 * To use the thunder file, I need to make sure that 
 * I added to the Path in the evironment variables.
 * I had to click the variable "Path" under 
 * the system variables and click edit. 
 * Then I copy the path "C:\xampp\php" 
 * and paste it in the variable value.
 * This allowed me to run the command "php thunder" 
 * in the terminal and use the thunder file.
 * 
 * 3/30/2025
 * I am currently working on the login and signup pages. 
 * I am using the bootstrap framework to create the forms.
 * I am deciding what columns to use with the database. 
 * I think I will include the employee number.
 * I also need to figure out how to handle the middle 
 * name field because I want it to be optional.
 * for now, I have removed the error function for 
 * the middle name field because it is not required.
 * I need to creaet a footer for the footer.php file. 
 * the one I have is not that great. 
 * I need to work on what the home page will display when the 
 * user is not logged in vs when the user is logged in.
 * 
 * 3/31/2025
 * I am currently working on the login and signup pages.
 * I managed to create a new user and login with that user.
 * I left the default password as "default_password" for now.
 * I will need to change this to a password that is based on the user's
 * full name and employee number.
 * I also need to create a password reset page 
 * that will allow the user to reset their password
 * using their email address.
 * I will need to create a hash value for the password reset link.
 * I will need to create a new column in the 
 * database to store this hash value.
 * I will also need to create a new function in the 
 * User model to handle the password reset process.
 * need to create a new view for the password reset page.
 * I will also need to create a new controller for the password reset page.
 * I want to send an automatic email to the user with the password reset link.
 * I will need to create a new function in the 
 * User model to handle the email sending process.
 * I also need to create a new view for the email template.
 * I created a access_granted column in the 
 * database to handle the login process.
 * This will be used for admins to change the 
 * value to 1 when the user is granted access.
 * 
 * 4/2/2025
 * I just managed to send an email to the user with a link 
 * to reset their password. I need to create the token to
 * reset the password and store it in the database. the token
 * will need to have an expiration date. I will need to create a new
 * column in the database to store the token and expiration date.
 * Create a forgot password page that will veryfy the token
 * and expiration date. if the token is valid, allow the user to reset 
 * their password. At this stage, the user will still need to get
 * verified by the admin before they can login. look at the 
 * previous date above to see what I need to do next. There are still
 * things I need to do in that list that I haven't done yet.
 * 
 * 4/7/2025
 * I am in the process of making the forgot password page.
 * I need to test and make sure that the teken_hash and the
 * token_expiry are being stored in the database.
 * Once that is done, I need to get the token from the email
 * and verify it with the database and not expired.
 * Come to think of it, I need to get the token_hash with the 
 * address of link that the user clicks on in the email. 
 * Thats what I need to do first, put the token_hash in th link
 * that the user clicks on in the email.
 * 
 * Go to user.php where the resetPassword function is.
 * look at the reset_password.php and do what you need to do
 * to make it work. 
 * 
 * 4/8/2025
 * I am working on the forgot password page. I need to make sure that the
 * token is being sent to the email and that the token is being stored in the
 * database. I also need to make sure that the token is being verified
 * when the user clicks on the link in the email.
 * Before I do that, I need to verify the user's email
 * 
 * 4/17/2025
 * I just finished working on the limit for the number
 * of login attempts. I need to make sure that the
 * user is locked out for after 5 failed attempts.
 * Now I need to work on making sure the user cannot go 
 * to the login page if they are already logged in.
 * After that I need to figure out how to time out
 * the session when the user is inactive 
 * for a certain amount of time.
 * 
 * 4/18/2025
 * I just finished creating the code to time out the session
 * I had to create a new controller called Set_time.php
 * This controller sets the $session->get('last_activity');
 * this will be recalled in the header.php file to 
 * check if the user is inactive for 30 minutes.
 * Since this is in the header.php file, it will be called on every page.
 * I don't know if there is a better way, I couldn't find
 * a way using any of the models in the framework. I thought 
 * about doing this in the Session model or in the User model,
 * but I couldn't find a way to do it. I hope I didn't 
 * compromise the security of the app by doing this.
 * 
 * 4/18/2025
 * I discovered that when the user is logged out
 * they can still access pages that are only supposed to be
 * accessible to logged in users. I need to create a
 * way to only allow logged in users to access certain pages.
 * 
 * you need to finish creating the Tokes.php model.
 * I've already started creating the model. I need to 
 * figure out how the table will be created and 
 * how I will display the information on the home page when
 * the user is logged in. I also need to figure out how
 * I will display the information on the home page when
 * the user is not logged in.
 * 
 * 4/21/2025
 * I've improved the footer and header files. 
 * At this point I need to focus on the actual website.
 * I need to decide what exactly the home page will display.
 * I also need to decide what pages will be created since
 * I keep changing my mind about it. 
 * 
 * 4/22/2025
 * I started working on the tables for the dailytokes and
 * and payperiods. I need to figure out how to display the
 * information on the home page when the user is logged in.
 * But before I do that, I need to populate the data for the
 * payperiods table first because deleting the dailytokes data
 * that needs to be deleted is based on the payperiods table's
 * end of payperiod data. 
 * 
 * I ran into another problem. I need to transfer data from
 * an excel file to the database. I had to do this in 
 * mysql workbench, which was nice so far. I still need to 
 * finished editing the table to the correct data types.
 * After that I will work on making a migration that will
 * at least create the columns without data. so far, i've 
 * converted the string to dates, now I need to convert the rest
 * of the string into currency. 
 */