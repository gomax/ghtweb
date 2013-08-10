<div class="page-title">
	<h1>Редактирование аккаунта <?php echo $this->uri->segment(4) ?></h1>
</div>

<?php echo $message ?>

<form action="" method="POST">
    
    <div class="row">
        <label for="password">Новый пароль</label>
        <div class="controls">
            <input type="password" name="password" id="password" />
        </div>
    </div>
    <div class="row">
		<label></label>
		<div class="controls">
			<button type="submit" name="submit">Изменить</button> <a href="/cabinet/accounts/" style="margin-left: 10px;">назад</a>
		</div>
	</div>

</form>