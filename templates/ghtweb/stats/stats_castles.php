<table class="table">
    <tbody>
        <?php foreach($stats_content as $row) { ?>
            <tr>
                <th colspan="2"><?php echo $row['name'] ?></th>
            </tr>
            <tr>
                <td width="150"><img src="/resources/images/castles/<?php echo $row['castle_id'] ?>.jpg" alt="" /></td>
                <td>
                Налог: <i><?php echo $row['tax_percent'] ?>%</i><br />
                Дата осады: <i><?php echo date('Y-m-d H:i:s', substr($row['sieg_date'], 0, 10)) ?></i><br />
                Владелец: <?php echo ($row['owner'] ? ($stats_clan_info ? anchor('stats/' . $server_id . '/clan_info/' . $row['owner_id'], $row['owner']) : $row['owner']) : '<i>NPC</i>') ?> <br />
                Нападающие:
                <?php
                $f = '';
                if($row['forwards'] && is_array($row['forwards']))
                {
                    foreach($row['forwards'] as $fd)
                    {
                        $f .= ($stats_clan_info ? anchor('stats/' . $server_id . '/clan_info/' . $fd['clan_id'], $fd['clan_name']) : $fd['clan_name']) . ', ';
                    }
                    
                    $f = substr($f, 0, -2);
                }
                else
                {
                    $f = '<i>Нет</i>';
                }
                echo $f;
                ?> <br />
                Защитники:
                <?php
                $f = '';
                if($row['defenders'] && is_array($row['defenders']))
                {
                    foreach($row['defenders'] as $fd)
                    {
                        $f .= ($stats_clan_info ? anchor('stats/' . $server_id . '/clan_info/' . $fd['clan_id'], $fd['clan_name']) : $fd['clan_name']);
                    }
                }
                else
                {
                    $f = '<i>Нет</i>';
                }
                echo $f;
                ?> <br />
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>