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
 * 
 * 4/23/2025
 * I just learned the hard way to make sure I have 
 * table backups since sql statements cannot be undone.
 * I am still working on making sure I have the correct 
 * data types for the columns in the database. This is 
 * a lot harder than I thought. I can't move on with my 
 * coding unless I have this done. Once I get this done, I
 * can create the migration file with the correct data types.
 * 
 * 4/24/2025
 * I had some issues with the database. I had to 
 * unstall and reinstall xampp so make it work again.
 * I have to redo the database and get back to speed to 
 * where I left off. 
 * 
 * I fixed the issues with the database. the table for the
 * payperiod is complete. I need to create the code to 
 * access the table and display the data on the home page.
 * Once I do that... let's just focus on this for now.
 * 
 * I created teh getCurrentPayperiod function that returns 
 * a string in date format. This will be used to get the 
 * starting point for the other table containing daily drops.
 * 
 * I updated the home paga, dailytokes migration and 
 * made it so the user can only see the daily 
 * tokes page whe logged in.
 * 
 * 4/27/2025
 * 
 * I need to create the getPreviousPayperiod function 
 * and test it out before I move on. 
 * 
 * I completed the function to ge the previous pay period.
 *  I also learned a bit more abouit the view() 
 * and how to use the $data. I may need to double
 * check this to make sure it works correctly. 
 * Knowing what I do with the view() function, I can
 * change the way the sessions works... or maybe I should
 * leave it as is. I don't know yet.
 * 
 * 4/28/2025
 * I am working on creating the updateDailyDropsTable function.
 * This function will be used to update the daily drops table
 * I need to figure out how I will do this. Right now, I am thinking
 * of updating the date_drop column with the current date and set
 * an expiry date for the current payperiod and the previous payperiod.
 * 
 * 4/30/2025
 * I need to continue to work on the populateDates function.
 * this will help populate the table with the correct dates
 * when there are no dates later than the current date. 
 * I will then pupulate the table if this is the case. 
 * I will use the last the end_pp entry to see where does the curerent day compares
 * to the end of the payperiod. I will then generate the dates until it 
 * reaches the current pay period. I need to keep in mind that 
 * the dates are payperiods and not daily drops. This should update
 * whenever the user logs in. 
 * 
 * 5/1/2025
 * I just finished the populateDates function.
 * the function adds the dates to the table if there are no
 * dates in the table that are greater than the current date.
 * this is based on the start_pp column. The function will 
 * automatically add the dates to the table.
 * 
 * I need to work on the updateDailyDropsTable function.
 * I made some changes to improve the code for when the table is empty.
 * I still need to work on it, and I will need to figure out how to 
 * remove the outdated data from the table and shift the current
 * that has been outdated to the previous payperiod. Then I can update 
 * the dropdate values to the correct previoius and current payperiods
 * dates in the dailytokes table.
 * 
 * 5/4/2025
 * I manage to update the expiry date along witht the date_drop dates
 * this will only occure when the table is empty or has empty or null values
 * within the date_drop and expiry columns. Now I need to figure out how to use
 * the expiry date to shift the table so that the current payperiod becomes 
 * the previous payperiod and the new payperiod becomes the current payperiod.
 * 
 * 5/8/2025
 * 
 * I managed to update the daily drops table automatically based on the 
 * current day and shift the table to make room for the new pay period. 
 * I also discovered if the user logged out and hit the back button,
 * they would be able to visit previous pages they visited as if they were
 * logged in. I fixed this by putting session_regenerate_id(true) in the 
 * logout function.
 * 
 * At this point, I need to populate the webpage with the toke 
 * rate data. I will try and use bootstrap. 
 * 
 * 5/15/2025
 * I need to make sure that whenever I am done creading this app,
 * I'll need to set the value false in the config.php file where
 * it says "define('DEBUG', true);". This will not show any errors 
 * that will expose information about the app. 
 * 
 * 5/18/2025
 * I am currently working on the dailytokes tables. I managed to
 * display the data with the correct format. Now I need to somehow display
 * toke rate for the previous payperiod and make a prediction toke rate for
 * the current payperiod. I don't know how I will do this yet. Also, make a new
 * branch so that I am not working on the main branch.
 */