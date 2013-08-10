<div class="page-title">
	<h1>Добавление игрового аккаунта</h1>
</div>

<?php echo $message ?>

<?php if($this->config->item('snap_game_account_allow')) { ?>
    <form action="" method="POST">
        
        <?php if(count($logins) > 1) { ?>
            <div class="row">
                <label>Выберите сервер</label>
                <div class="controls">
                    <?php echo form_dropdown('login_id', $logins_for_select) ?>
                </div>
            </div>
        <?php } ?>
        
        <div class="row">
            <label for="login">Логин</label>
            <div class="controls">
                <input type="text" name="login" id="login" />
            </div>
        </div>
        <div class="row">
            <label for="password">Пароль</label>
            <div class="controls">
                <input type="password" name="password" id="password" />
            </div>
        </div>
        <div class="row">
    		<label></label>
    		<div class="controls">
    			<button type="submit" name="submit">Добавить</button>
    		</div>
    	</div>

    </form>
<?php } ?>