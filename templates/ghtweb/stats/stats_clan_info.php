<p class="servers-list">Состав клана: <?php echo $clan_name ?></p>

<table class="table">
    <thead>
        <tr>
            <th width="57%">Ник</th>
            <th width="12%">ПВП/ПК</th>
            <th width="21%">Время в игре</th>
            <th width="10%">Статус</th>
        </tr>
    </thead>
    <tbody>
        <?php if($stats_content) { ?>
            <?php foreach($stats_content as $row) { ?>
                <tr>
                    <td><?php echo $row['char_name'] ?>
                        <p class="desc" style="font-size: 11px;"><?php echo get_class_name_by_id($row['base_class']) ?> [<?php echo $row['level'] ?>]</p></td>
                    <td><?php echo $row['pvpkills'] ?>/<?php echo $row['pkkills'] ?></td>
                    <td><?php echo online_time($row['onlinetime']) ?></td>
                    <td><?php echo ($row['online'] ? 'Онлайн' : 'Оффлайн') ?></td>                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4">Данных нет</td>
            </tr>
        <?php } ?>
    </tbody>
</table>