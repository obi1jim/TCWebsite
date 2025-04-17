<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Mailer class
 */
class Mailer
{
	use Model;
	//use \PHPMailer\PHPMailer\Exception;
	//use \PHPMailer\PHPMailer\PHPMailer;
	//use \PHPMailer\PHPMailer\SMTP;
	

	public function sendMail($email, $subject, $message){
		//echo "<br> inside the sendMail function";
	
		//Creating a new PHPMailer object.
		$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
		
		//this will show debuggin outputs. Caution: This should only be used when debugging and not in use. remove the comment to show debug.
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
	
		//Using the SMTP protocol to send the mail
		$mail->isSMTP();//method
	
	
		//Here we are setting the SMATPAuth property to true, so we can use our Gmail login dtails to send the mail.
		$mail->SMTPAuth = true;
	
		//Stting the Host property t the MAILHOST value that we defined in the config-mail file
		$mail->Host = MAILHOST;//this is the smtp.gmail.com
	
		//Setting th Username property to the USERNAME vlaue that we defined in the config-mail file.
		$mail->Username = USERNAME;
	
		//Setting the Password to the PASSWORD value that was defined in the config-mail file.
		$mail->Password = MAIL_PASSWORD;
	
		/*
		 * By setting SMTPSecure to PHPMailer:: ENCRYPTION_STARTTLS, we are telling PHPMailer
		 * to use the STARTTLS encryption method when connecting to the SMTP server. This
		 * helps ensure that the communication between your PHP application and the SMTP server
		 * is encrypted, adding a layer of security to the email sending process.
		 * */
	
		$mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
	
		//TCP port to connect with the Gmaiol SMTP server
		$mail->Port = 587;
	
		//this uses a function setFrom to set the email sending the email. These are the constant that were defined in the config-mail.php file.
		$mail->setFrom(SEND_FROM, SEND_FROM_NAME);
		
		//This method takes in a value to where the email is sent to or where the email goes.
		$mail->addAddress($email);//the $email variable is one of the parameter of this function. it will be specified when called.
		
		//the 'addReplyTo' property specifies where the recipient can reply to. This is defined in the config-mail.php file.
		$mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);
	
		/*Setting the $mail->IsHTML(true), it informs PHPMailer that the email message includes HTML markup.
		 * This is important when we want to send emails with HTML formatting
		 * which allows us to include things like hyperlinks, images, formatting,
		 * and other HTML elements in our email content*/
		$mail->IsHTML(true);
	
		//Assigning the incoming subject to the $mail->subject property.
		//note that the Subject is part of the PHPMailer namespace. the $subject is just the parameter of this function.
		$mail->Subject = $subject;
	
		//Assigning the incoming message to the $mail->body property
		$mail->Body = $message;
	
		/*When we set $mail->AltBody, we are providing a plain text alternative
		 * to the HTML version of our email
		 * This is importatn for compartibility with email clients that may not support
		 * or display HTMl content.
		 * In cases like that, the email client will display the plain text content instead of the HTML content.
		 * */
		$mail->AltBody = $message;
		//echo "<br><br> Testing";
		
		return $mail->send();//this will return true or false depending on the success of the mail sending process.
		
	}



}