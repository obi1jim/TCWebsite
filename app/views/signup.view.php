<?php $this->view("includes/header", $data); ?>

<div class="mx-auto col-md-4 bg-light shadow m-4 p-4 border">
	<h1>Signup</h1>
	<form method="post">

		<!-- the name="first_name" needs to match the value in the 
		 getError function string parameter. this is used along with
		 the User.php variable onInsertValidationRules and the validation()
		 in the Model.php. There I can make a function that can edit the 
		 first_name string and replace the string with a more grammatical 
		 version such as First Name when its displayed instad of first_name
		 I may need to do this in within the -->
		 <input class="my-3 form-control" value="<?=old_value('employee_number')?>" name="employee_number" placeHolder="Employee Number (900 or 9000)">
		<div><small class="text-danger"><?=$user->getError('employee_number')?></small></div>

		<input class="my-3 form-control" value="<?=old_value('full_name')?>" name="full_name" placeHolder="Full Name">
		<div><small class="text-danger"><?=$user->getError('full_name')?></small></div>

		<input class="my-3 form-control" value="<?=old_value('email')?>" name="email" placeHolder="Email">
		<div><small class="text-danger"><?=$user->getError('email')?></small></div>
		
		
		<button class="my-3 btn btn-primary">Signup</button>
	</form>
</div>
<?php $this->view("includes/footer", $data); ?>