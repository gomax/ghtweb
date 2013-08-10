<div class="page-title">
	<h1>Регистрация Мастер аккаунта</h1>
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
		<label for="email">Email</label>
		<div class="controls">
			<input type="text" name="email" id="email" value="<?php echo set_value('email') ?>" />
		</div>
	</div>
	<div class="row">
		<label for="password">Пароль</label>
		<div class="controls">
			<input type="password" name="password" id="password" />
		</div>
	</div>
	<div class="row">
		<label for="repassword">Повтор пароля</label>
		<div class="controls">
			<input type="password" name="repassword" id="repassword" />
		</div>
	</div>
	<div class="row">
		<label></label>
		<div class="controls">
			<div class="captcha"><?php echo $captcha ?></div>
		</div>
	</div>
	<div class="row">
		<label for="captcha">Код с картинки</label>
		<div class="controls">
			<input type="text" name="captcha" id="captcha" />
		</div>
	</div>
	<div class="row">
		<label></label>
		<div class="controls">
			<button type="submit" name="submit">Зарегистрироваться</button>
		</div>
	</div>
	
</form>