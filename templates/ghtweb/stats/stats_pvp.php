<table class="table">
    <thead>
        <tr>
            <th width="35%">Ник</th>
            <th width="14%">Кол-во ПВП</th>
            <th width="20%">Клан</th>
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
                    <td><?php echo $row['pvpkills'] ?></td>
                    <td><?php
                    $clan_link = $row['clan_name'];
                    
                    if($clan_info)
                    {
                        $clan_link = anchor('stats/' . $server_id . '/clan_info/' . $row['clan_id'], $row['clan_name']);
                    }
                    
                    echo ($row['clan_name'] == '' ? 'Не в клане' : $clan_link);
                    ?></td>
                    <td><?php echo online_time($row['onlinetime']) ?></td>
                    <td><?php echo ($row['online'] ? 'Онлайн' : 'Оффлайн') ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">Данных нет</td>
            </tr>
        <?php } ?>
    </tbody>
</table>