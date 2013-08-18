<div class="page-title">
    <h1>Персонажи аккаунта <?php echo $this->uri->segment(4) ?></h1>
</div>

<?php echo $message ?>

<?php if($content) { ?>
    
    <?php foreach($content as $server_id => $row) { ?>
        
        <h3 class="servers-list">Сервер: <i><?php echo $row['server_name'] ?></i></h3>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="54%">Ник персонажа</th>
                    <th width="21%">Время в игре</th>
                    <th width="10%">Статус</th>
                    <th width="10%">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($row['characters'] as $i => $character) { ?>
                    <tr>
                        <td><?php echo ++$i ?></td>
                        <td><?php echo html_escape($character['char_name']) ?></td>
                        <td><?php echo online_time($character['onlinetime']) ?></td>
                        <td><?php echo ($character['online'] ? 'Онлайн' : 'Оффлайн') ?></td>
                        <td>
                            <menu>
                                <li><a href="/cabinet/characters/teleport/<?php echo $server_id ?>/<?php echo $character['char_id'] ?>/<?php echo $character['account_name'] ?>/">В город</a></li>
                            </menu>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
    <?php } ?>

<?php } ?>

<a href="/cabinet/accounts/">назад</a>