<?php 

defined('ROOTPATH') OR exit('Access Denied!');

/** check which php extensions are required **/
check_extensions();
function check_extensions()
{

	$required_extensions = [

		'gd',
		'mysqli',
		'pdo_mysql',
		'pdo_sqlite',
		'curl',
		'fileinfo',
		'intl',
		'exif',
		'mbstring',
	];

	$not_loaded = [];

	foreach ($required_extensions as $ext) {
		
		if(!extension_loaded($ext))
		{
			$not_loaded[] = $ext;
		}
	}

	if(!empty($not_loaded))
	{
		show("Please load the following extensions in your php.ini file: <br>".implode("<br>", $not_loaded));
		die;
	}
}


function show($stuff)
{
	echo "<pre>";
	print_r($stuff);
	echo "</pre>";
}

function esc($str)
{
	return htmlspecialchars($str);
}

function redirect($path)
{
	header("Location: " . ROOT."/".$path);
	die;
}

/** load image. if not exist, load placeholder **/
function get_image(mixed $file = '',string $type = 'post'):string
{

	$file = $file ?? '';
	if(file_exists($file))
	{
		return ROOT . "/". $file;
	}

	if($type == 'user'){
		return ROOT."/assets/images/user.webp";
	}else{
		return ROOT."/assets/images/no_image.jpg";
	}

}


/** returns pagination links **/
function get_pagination_vars():array
{
	$vars = [];
	$vars['page'] 		= $_GET['page'] ?? 1;
	$vars['page'] 		= (int)$vars['page'];
	$vars['prev_page'] 	= $vars['page'] <= 1 ? 1 : $vars['page'] - 1;
	$vars['next_page'] 	= $vars['page'] + 1;

	return $vars;
}


/** saves or displays a saved message to the user **/
function message(string $msg = null, bool $clear = false)
{
	$ses 	= new Core\Session();

	if(!empty($msg)){
	    $ses->set('message',$msg);
	}else
	if(!empty($ses->get('message'))){
	    
	    $msg = $ses->get('message');
	    
	    if($clear){
	      $ses->pop('message');
	    }
	    return $msg;
	}
	
	return false;
}

/** return URL variables **/
function URL($key):mixed
{
	$URL = $_GET['url'] ?? 'home';
	$URL = explode("/", trim($URL,"/"));
	
	switch ($key) {
		case 'page':
		case 0:
			return $URL[0] ?? null;
			break;
		case 'section':
		case 'slug':
		case 1:
			return $URL[1] ?? null;
			break;
		case 'action':
		case 2:
			return $URL[2] ?? null;
			break;
		case 'id':
		case 3:
			return $URL[3] ?? null;
			break;
		default:
			return null;
			break;
	}

}


/** displays input values after a page refresh **/
function old_checked(string $key, string $value, string $default = ""):string
{

  if(isset($_POST[$key]))
  {
    if($_POST[$key] == $value){
      return ' checked ';
    }
  }else{

    if($_SERVER['REQUEST_METHOD'] == "GET" && $default == $value)
    {
      return ' checked ';
    }
  }

  return '';
}


function old_value(string $key, mixed $default = "", string $mode = 'post'):mixed
{
  $POST = ($mode == 'post') ? $_POST : $_GET;
  if(isset($POST[$key]))
  {
    return $POST[$key];
  }

  return $default;
}

function old_select(string $key, mixed $value, mixed $default = "", string $mode = 'post'):mixed
{
  $POST = ($mode == 'post') ? $_POST : $_GET;
  if(isset($POST[$key]))
  {
    if($POST[$key] == $value)
    {
      return " selected ";
    }
  }else

  if($default == $value)
  {
    return " selected ";
  }

  return "";
}

/** returns a user readable date format **/
function get_date($date)
{
	return date("jS M, Y",strtotime($date));
}


/** comverts image paths from relative to absolute **/
function add_root_to_images($contents)
{

  preg_match_all('/<img[^>]+>/', $contents, $matches);
  if(is_array($matches) && count($matches) > 0) {

    foreach ($matches[0] as $match) {

      preg_match('/src="[^"]+/', $match,$matches2);
      if(!strstr($matches2[0], 'http'))
      {
      
        $contents = str_replace($matches2[0], 'src="'.ROOT.'/'.str_replace('src="',"",$matches2[0]), $contents);
      }
    }

  }

  return $contents;
}

/** converts images from text editor content to actual files **/
function remove_images_from_content($content,$folder = "uploads/")
{

    if(!file_exists($folder)){
        mkdir($folder,0777,true);
        file_put_contents($folder."index.php","Access Denied!");
    }

    //remove images from content
    preg_match_all('/<img[^>]+>/', $content, $matches);
    $new_content = $content;

    if(is_array($matches) && count($matches) > 0) {

        $image_class = new \Model\Image();
        foreach ($matches[0] as $match) {
            
            if(strstr($match, "http"))
            {
            	//ignore images with links already
            	continue;
            }

            // get the src
            preg_match('/src="[^"]+/', $match,$matches2);
            
            // get the filename
            preg_match('/data-filename="[^\"]+/', $match,$matches3);

            if(strstr($matches2[0], 'data:'))
            {

              $parts = explode(",",$matches2[0]);
              $basename = $matches3[0] ?? 'basename.jpg';
              $basename = str_replace('data-filename="', "", $basename);

              $filename = $folder . "img_" . sha1(rand(0,9999999999)) . $basename;

              $new_content = str_replace($parts[0] . ",". $parts[1], 'src="'.$filename, $new_content);
              file_put_contents($filename, base64_decode($parts[1]));

              //resize image
              $image_class->resize($filename,1000);
            }
           
        }
    }

    return $new_content;

}

/** deletes images from text editor content **/
function delete_images_from_content(string $content, string $content_new = ''):void
{
 
    //delete images from content
	if(empty($content_new))
	{

    preg_match_all('/<img[^>]+>/', $content, $matches);

    if(is_array($matches) && count($matches) > 0) {
        foreach ($matches[0] as $match) {
            
            preg_match('/src="[^"]+/', $match,$matches2);
            $matches2[0] = str_replace('src="',"",$matches2[0]);

            if(file_exists($matches2[0]))
            {
              unlink($matches2[0]);
            }

        }
    }
  }else{

  	//compare old to new and delete from old what inst in the new
  	preg_match_all('/<img[^>]+>/', $content, $matches);
  	preg_match_all('/<img[^>]+>/', $content_new, $matches_new);

  	$old_images = [];
  	$new_images = [];

  	/** collect old images **/
    if(is_array($matches) && count($matches) > 0) {
        foreach ($matches[0] as $match) {
            
            preg_match('/src="[^"]+/', $match,$matches2);
            $matches2[0] = str_replace('src="',"",$matches2[0]);

            if(file_exists($matches2[0]))
            {
              $old_images[] = $matches2[0];
            }

        }
    }

    /** collect new images **/
    if(is_array($matches_new) && count($matches_new) > 0) {
        foreach ($matches_new[0] as $match) {
            
            preg_match('/src="[^"]+/', $match,$matches2);
            $matches2[0] = str_replace('src="',"",$matches2[0]);

            if(file_exists($matches2[0]))
            {
              $new_images[] = $matches2[0];
            }

        }
    }


    /** compare and delete all that dont appear in the new array **/
    foreach ($old_images as $img) {
    	
    	if(!in_array($img, $new_images))
    	{

    		if(file_exists($img)){
    			unlink($img);
    		}
    	}
    }
  }

}
//converst a string with underscores to a string with spaces and capitalizes the first letter of each word
// this is used to display the name of the field in the form instead of the field name
function underscore_to_space_str(string $str):string
{
  //replace underscores with spaces
  $str = str_replace("_", " ", $str);
  //capitilaze first letter of each word
  $str = ucwords($str);

  return $str;
}

function sendMail($email, $subject, $message){
  //echo "<br> inside the sendMail function";

  //Creating a new PHPMailer object. I had to use the 
  //full namespace because of the autoloading system.
  //this is the PHPMailer class that we are using to send the email. 
  //This class is part of the PHPMailer library located in the core folder
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

   //had to use the full namespace because of the autoloading system.
  //this is the PHPMailer class that we are using to send the email.
  $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;

  //TCP port to connect with the Gmail SMTP server
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

function autoPassword($name, $employee_number):string
{
  //this will create a password based on the user's name and employee number.
  //this will be used to create a default password for the user.
  $strWords = explode(" ", $name);
  $name = $strWords[0];
  $password = "";
  for($i = 0; $i < count($strWords); $i++) {
    $password .= substr($strWords[$i], 0, 1);
  }
  $password .= substr($employee_number,-5);
  return strtolower($password);
}