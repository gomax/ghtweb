<div class="page-title">
	<h1>Восстановление пароля от Мастер аккаунта</h1>
</div>

<?php echo $message ?>

<form action="" method="POST" class="form">
    
    <div class="row">
        <label for="login">Логин</label>
        <div class="controls">
            <input type="text" name="login" id="login" />
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
			<button type="submit" name="submit">Восстановить</button>
		</div>
	</div>
    
</form>