<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * User class
 */
class User //extends \Model\Mailer
{
	
	use Model;

	protected $table = 'users';
	protected $primaryKey = 'id';
	protected $emailUniqueColumn = 'email';
	protected $loginUniqueColumn = 'employee_number';
	 //this is the column that will be used to login the user.

	protected $allowedColumns = [

		'employee_number',
		'email',
		'password',
		'full_name',
		'access_granted',
		'token_hash',
		'token_expiry',
	];

	/*****************************
	 * 	rules include:
		required
		alpha
		email
		numeric
		unique
		symbol
		longer_than_8_chars
		alpha_numeric_symbol
		alpha_numeric
		alpha_symbol
	 * 
	 ****************************/
	protected $onInsertValidationRules = [

		'email' => [
			'email',
			'unique',
			'required',
		],
		'employee_number' => [
			'numeric',
			'unique',
			'required',
		],
		'password' => [
			'not_less_than_8_chars',
			'required',
		],
		'full_name' => [
			'alpha_space',
			'required',
		],
	];

	protected $onUpdateValidationRules = [

		'email' => [
			'email',
			'required',
		],
		'employee_number' => [
			'numeric',
			'required',
		],
		'password' => [
			'not_less_than_8_chars',
			'required',
		],
		'full_name' => [
			'alpha_space',
			'required',
		],
		
	];

	public function signup($data)
	{
		if($this->validateSignUp($data))
		{
			//add extra user columns here

			
			//defualt password based on the user's full name and employee number.
			$data['password'] = autoPassword($data['full_name'], $data['employee_number']); 
			//show($data['password']);//this is just to show me what that password looks like. 

			$token = bin2hex(random_bytes(16));
			$token_hash = hash("sha256", $token);

			date_default_timezone_set('America/New_York');
			$token_expiry = date("Y-m-d H:i:s", time() + 60 * 30);

			$data['token_hash'] = $token_hash;
			$data['token_expiry'] = $token_expiry;
			//$data['access_granted'] = 0; //default value for access granted. this will be changed to 1 when the user is granted access.

			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

			//$data['role'] = 'user'; //default role, in case we have roles in the future.
			//such as admin, user, etc.

			//$data['date'] = date("Y-m-d H:i:s");
			//$data['date_created'] = date("Y-m-d H:i:s");


			ob_start();
			//include("emailMessageTemplate.php");
			
			include("emailMessageTemplate.php");

			$emailMessage = ob_get_contents();

			ob_get_clean();
			//I didn't want to make Mailer a parent class of User.
			//$response = $this->sendMail($data['email'], "Signed Up!", $emailMessage);

			//this is sendMail in the functions.php file.
			$response = sendMail($data['email'], "Signed Up!", $emailMessage);

			if($response){

				$this->insert($data);
				//redirect('login');
				redirect('signup_success');
			}else{
				$this->errors['email'] = "Failed to send email!, data was not inserted into the database.";
			}

			

			//I also need to send them an email so that they may craete
			//their own password.
			//I will also need to create a hash value that will be used to reset the password.
		}
	}

	public function login($data)
	{
		$row = $this->first([$this->loginUniqueColumn=>$data[$this->loginUniqueColumn]]);

		if($row){

			//confirm password
			if(password_verify($data['password'], $row->password) && $row->access_granted == 1)
			{
				$ses = new \Core\Session;
				$ses->auth($row);
				/** This is for when I create a role variable that will
				 * be used to redirect the user to the right page or the
				 * admin page. this informations will be stored in the
				 * in the database and will be used to redirect the user to the right page.
				 * 
				 * switch($row->role){
				 * 	case 'admin':
				 * 		redirect('admin');
				 * 		break;
				 * 	case 'user':
				 * 		redirect('home');
				 * 		break;
				 * 	default:
				 * 		redirect('home');
				 * 		break;
				 * }
				*/
				redirect('home');
			}else{
				if($row->access_granted == 0){
					$this->errors[$this->loginUniqueColumn] = "Your account is not activated yet. Verification process must take place. Contact developer for help.";
				}else{
					$this->errors[$this->loginUniqueColumn] = "Wrong ". underscore_to_space_str($this->loginUniqueColumn) . " or Password";
				}
			}
		}else{
			//$this->errors[$this->loginUniqueColumn] = "Wrong $this->loginUniqueColumn or password";
			$this->errors[$this->loginUniqueColumn] = "Wrong ". underscore_to_space_str($this->loginUniqueColumn) . " or Password";
		}
	}

	public function resetPassword($data, $token = null)
	{
		$employee_number = $data['employee_number'];
		
		if($this->validateSignIn($data))
		{

			//this is the token hash value that will be used to check if the token is valid or not.
			//this is done by hashing the token value and comparing it to the one in the database.
			//this is done for security reasons.
			$token_hash = hash("sha256", $token);
			//show(['token_hash'=>hash("sha256", $data['token'])]);

			date_default_timezone_set('America/New_York');
			
			//this extracts the user on the table that has the token hash value
			//and assigns it to the $row variable.
			//the row variable will be used to check if the token is valid or not.
			$row = $this->first(['employee_number' => $employee_number]);                         
			//echo "first time";
			//echo "<br>";
			//show($row);
			
			//for some reason, when the tokens are empty, there is an error in php
			//I put this here to avoid displaying the error message.
			if(empty($row->token_hash) || empty($row->token_expiry)){
				$this->errors['token'] = "Token is invalid or expired.";
				// Show alert and redirect
				echo "<script>
					alert('Failed to reset password. Contact developer for help. Redirecting to the home page.');
					window.location.href = '" . ROOT . "';
					</script>";
					die;
			}
			if($row->token_hash == $token_hash && $row->token_expiry > date("Y-m-d H:i:s"))
			{

				//$row->token_hash = null;
				//$row->token_expiry = null;
				//$row->password = password_hash($data['password'], PASSWORD_DEFAULT);

				$this->update($row->id, [
					'token_expiry' => date("Y-m-d H:i:s"),
					'password' => password_hash($data['password'], PASSWORD_DEFAULT),
				]);
				//echo "second time";
				//$row = $this->first(['employee_number' => $employee_number]);
				//show($row);
				// Show alert and redirect
				if($row->access_granted == 0){
					echo "<script>
					alert('Password was successfully updated. Your account will be in review. You will be notified when you are granted access. Redirecting to the login page.');
					window.location.href = '" . ROOT . "/login';
					</script>";
					
				}else{
					echo "<script>
					alert('Password was successfully updated. Redirecting to the login page.');
					window.location.href = '" . ROOT . "/login';
					</script>";
					
				}
				exit;
			}else{	
				if($this->where(['employee_number' => $employee_number]) == false){
					$this->errors['employee_number'] = "Employee number is invalid.";
					
				}else{
					
					// Show alert and redirect
					echo "<script>
					alert('Failed to reset password. Contact developer for help. Redirecting to the login page.');
					window.location.href = '" . ROOT . "/login';
					</script>";
					die;
				}
			}
		}
		
		
		
	}
	public function forgotPassword($data)
	{
		$data['employee_number'] = htmlspecialchars($data['employee_number'], ENT_QUOTES, 'UTF-8');
		$data['email'] = htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8');
		if($this->validateSignIn($data)){
			$row = $this->first(['employee_number' => $data['employee_number'], 'email' => $data['email']]);
			if($row == false){
				$this->errors['employee_number'] = "Employee number or email is invalid.";
				// Show alert and redirect
				echo "<script>
					alert('Employee number or email is invalid. Redirecting to the login page.');
					window.location.href = '" . ROOT . "/login';
					</script>";
					die;
			}
			else{
				$token = bin2hex(random_bytes(16));
				$token_hash = hash("sha256", $token);
				$data['token_hash'] = $token_hash;
				$data['full_name'] = $row->full_name;

				date_default_timezone_set('America/New_York');
				$token_expiry = date("Y-m-d H:i:s", time() + 60 * 30);
				$data['token_expiry'] = $token_expiry;
				//show($data['token_expiry']);

				ob_start();

				include("emailMessageForgotPassword.php");
				$emailMessage = ob_get_contents();

				ob_get_clean();

				$response = sendMail($data['email'], "Reset Password", $emailMessage);
				if($response){
					echo "An Email has been sent to you with a link to reset your password. Please check your email.";
					$this->update($row->id, [
						'token_hash' => $token_hash,
						'token_expiry' => $token_expiry,
					]);
					redirect('signup_success');
				}else{
					$this->errors['email'] = "Failed to send email!, data was not inserted into the database. Notify the developer Jimmy to fix this issue";
				}
					

			}
		
		}
	}

}