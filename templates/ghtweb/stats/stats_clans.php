<table class="table">
    <thead>
        <tr>
            <th width="35%">Название клана</th>
            <th width="10%">Уровень</th>
            <th width="15%">Замок</th>
            <th width="10%">Игроков</th>
            <th width="10%">Репутация</th>
            <th width="20%">Альянс</th>
        </tr>
    </thead>
    <tbody>
        <?php if($stats_content) { ?>
            <?php foreach($stats_content as $row) { ?>
                <tr>
                    <td><?php
                    if($stats_clan_info)
                    {
                        echo anchor('stats/' . $server_id . '/clan_info/' . $row['clan_id'], $row['clan_name']);
                    }
                    else
                    {
                        echo '<font color="#9D6A1E">' . $row['clan_name'] . '</font>';
                    }
                    ?><p class="desc" style="font-size: 11px;">Лидер: <?php echo $row['char_name'] ?></p></td>
                    <td><?php echo $row['clan_level'] ?></td>
                    <td><?php echo get_castle_name($row['hasCastle']) ?></td>
                    <td><?php echo $row['ccount'] ?></td>
                    <td><?php echo number_format($row['reputation_score'], 0, '', '.') ?></td>
                    <td><?php echo ($row['ally_name'] != '' ? $row['ally_name'] : 'Не в Альянсе') ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6">Данных нет</td>
            </tr>
        <?php } ?>
    </tbody>
</table>