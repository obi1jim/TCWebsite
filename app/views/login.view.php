<?php $this->view("includes/header", $data); ?>

<div class="mx-auto col-md-4 bg-light shadow m-4 p-4 border ">
	<?php if(message()): ?>
		<div class="alert alert-success" role="alert">
			<?=message('', true)?>
		</div>
		<?php endif; ?>
	<h1>Login</h1>
	<form method="post">
		<input class="my-3 form-control" value="<?=old_value('employee_number')?>" name="employee_number" placeHolder="Employee Number">
		<div><small class="text-danger"><?=$user->getError('employee_number')?></small></div>
		<input type="password" class="form-control" value="<?=old_value('password')?>" name="password" placeHolder="Password">
		<div><small class="text-danger"><?=$user->getError('password')?></small></div>
		<div class="mt-2">
			<a href="<?=ROOT?>/forgot_password" class="text-decoration-none" style="font-size: 0.80em; color: steelblue;">Forgot Password?</a>
		</div>
		<button class="my-3 btn btn-primary">Login</button>
	</form>
</div>

<?php $this->view("includes/footer", $data); ?>