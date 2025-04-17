<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        
    </style>
    
</head>
<body>
    
    <h1>Welcome, <?php echo $name = isset($data['full_name']) ? $data['full_name'] : 'Fellow Dealer!';?></h1>
    <p>Use the link below to reset your password. This link will only be temporary. You have 30 minutes to reset your password from the time you received this email. If the link expires, just click the forgot password link on the login page.</p>
    
	<p><a href="http://localhost/TCWebsite/public/reset_password?token=<?=$token?>">Reset your password</a></p>
    
</body>
</html>

