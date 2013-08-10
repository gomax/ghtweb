<div class="alert alert-info">После нажатия кнопки "Далее" Вы переходите на сайт платежной системы, где производите оплату.</div>

<table class="table">
    <tbody>
        <tr>
            <td width="30%">Платёжная система</td>
            <td width="70%"><?php echo $payment_system ?></td>
        </tr>
        <tr>
            <td>Номер заявки</td>
            <td><?php echo $transaction_id ?></td>
        </tr>
        <tr>
            <td>Получаете</td>
            <td><?php echo $item_count ?> <?php echo $item_name ?></td>
        </tr>
        <tr>
            <td>Отдаёте</td>
            <td><?php echo number_format($sum, 0, '', ',') ?></td>
        </tr>
        <tr>
            <td>Персонаж</td>
            <td><?php echo $char_name ?></td>
        </tr>
        <tr>
            <td>Сервер</td>
            <td><?php echo $server_name ?></td>
        </tr>
    </tbody>
</table>

<form action="<?php echo $action ?>" method="post">
    <?php echo $form_fields ?>
    
    
    <div class="row">
        <div class="controls" style="margin-left: 0;">
            <button type="submit">Перейти к оплате</button>
            <a href="/deposit/" class="forgot-pswd">назад</a>
        </div>
    </div>
    
</form>