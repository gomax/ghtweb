<div class="page-title">
	<h1>Игровые аккаунты</h1>
</div>

<?php echo $message ?>

<?php if($accounts) { ?>
    
    <?php foreach($accounts as $login_id => $row) { ?>
        
        <h3 class="servers-list">Сервер(а): <i><?php echo implode(', ', $row['servers']) ?></i></h3>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Аккаунт</th>
                    <th width="40%">Информация о входе</th>
                    <th width="20%">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if($row['accounts']) { ?>
                    <?php foreach($row['accounts'] as $i => $account) { ?>
                        <tr>
                            <td><?php echo ++$i ?></td>
                            <td><?php echo $account['login'] ?></td>
                            <td><?php echo ($account['last_active'] ? date('Y-m-d H:i', substr($account['last_active'], 0, 10)) : 'В игру не заходил') ?></td>
                            <td>
                                <menu>
                                    <li><a href="/cabinet/characters/<?php echo $login_id ?>/<?php echo $account['login'] ?>/">Персонажи</a></li>
                                    <li><a href="/cabinet/edit-account/<?php echo $login_id ?>/<?php echo $account['login'] ?>/">Управление</a></li>
                                </menu>
                            </td>
                        </tr>
                    <?php }?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">Аккаунтов нет</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
            
    <?php } ?>

<?php } else { ?>
    Аккаунты не найдены
<?php } ?>