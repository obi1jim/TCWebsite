<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <style>
        
    </style>
    
</head>
<body>
    
    <h1>Welcome, <?php echo $name = isset($data['full_name']) ? $data['full_name'] : 'Fellow Dealer!';?></h1>
    <p>You are almost done. You will need to reset your password. However, there is a verification period where your information must be confirmed with the casino dealer list. Once your information has been verified by the admins, you account will be activated and you can just login with your employee number and password that you end up creating. </p>
    <h2>Click The Link!</h2>
    <p>Remember, You will create a password to complete the signup proccess, but will account will not be active until your information has been verified. You will be notified by email when your accout is activated.</p>
    <!-- I need to change this link to a view that will allow the user to reset their password. -->
	<p><a href="http://localhost/TCWebsite/public/reset_password?token=<?=$token?>">Reset your password</a></p>
    
</body>
</html>

