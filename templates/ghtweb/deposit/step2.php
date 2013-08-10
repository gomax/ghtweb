<div class="alert alert-info">
    Введите <b>Имя персонажа</b> и желаемое количество <b><?php echo $item_name ?></b>
</div>

<form action="" method="POST" class="form">
    <div class="row">
        <label for="count_items">Кол-во <?php echo $item_name ?></label>
        <div class="controls">
            <input type="text" name="count_items" id="count_items" value="<?php echo set_value('count_items', 1) ?>" />
        </div>
    </div>
    <div class="row">
        <label for="char_name">Имя персонажа</label>
        <div class="controls">
            <input type="text" name="char_name" id="char_name" value="<?php echo set_value('char_name') ?>" />
        </div>
    </div>
	<?php if(count($payment_system) > 1) { ?>
		<div class="row">
			<label for="char_name">Платёжная система</label>
			<div class="controls">
				<?php echo form_dropdown('payment_system', $payment_system) ?>
			</div>
		</div>
    <?php } else { ?>
		<input type="hidden" name="payment_system" value="<?php echo key($payment_system) ?>" />
	<?php } ?>
    <div class="row">
        <label></label>
        <div class="controls">
            <button type="submit">Продолжить</button>
            <a href="/deposit/" class="forgot-pswd">назад</a>
        </div>
    </div>
</form>