<div class="page-title">
	<h1>Вход в Мастер аккаунт</h1>
</div>

<?php echo $message ?>

<form action="" method="POST" class="form">
    
	<div class="row">
		<label for="login">Логин</label>
		<div class="controls">
			<input type="text" name="login" id="login" value="<?php echo set_value('login') ?>" />
		</div>
	</div>
	<div class="row">
		<label for="password">Пароль</label>
		<div class="controls">
			<input type="password" name="password" id="password" />
		</div>
	</div>
	<?php if($recaptcha !== FALSE) { ?>
		<div class="row">
			<label></label>
			<div class="controls">
				<div class="captcha"><?php echo $recaptcha ?></div>
			</div>
		</div>
	<?php } ?>
	
	<div class="row">
		<label></label>
		<div class="controls">
			<button type="submit" name="submit">Войти</button>
			<a href="/forgotten_password/" class="forgot-pswd">Забыли пароль?</a>
		</div>
	</div>

</form>