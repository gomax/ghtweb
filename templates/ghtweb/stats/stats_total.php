<table class="table">
    <tr>
        <th colspan="2">Разное</th>
    </tr>
    <tr>
        <td width="30%">В игре</td>
        <td width="70%"><?php echo $stats['online'] ?></td>
    </tr>
    <tr>
        <td>Аккаунтов</td>
        <td><?php echo $stats['accounts'] ?></td>
    </tr>
    <tr>
        <td>Персонажей</td>
        <td><?php echo $stats['characters'] ?></td>
    </tr>
    <tr>
        <td>Кланов</td>
        <td><?php echo $stats['clans'] ?></td>
    </tr>
    <tr>
        <td>Мужчин</td>
        <td><?php echo $stats['men'] ?></td>
    </tr>
    <tr>
        <td>Женщин</td>
        <td><?php echo $stats['women'] ?></td>
    </tr>
    <tr>
        <th colspan="2">Расы</th>
    </tr>
    <tr>
        <td>Люди</td>
        <td><?php echo $stats['human'] ?>%</td>
    </tr>
    <tr>
        <td>Эльфы</td>
        <td><?php echo $stats['elf'] ?>%</td>
    </tr>
    <tr>
        <td>Тёмные Эльфы</td>
        <td><?php echo $stats['dark_elf'] ?>%</td>
    </tr>
    <tr>
        <td>Орки</td>
        <td><?php echo $stats['orc'] ?>%</td>
    </tr>
    <tr>
        <td>Гномы</td>
        <td><?php echo $stats['dwarf'] ?>%</td>
    </tr>
    <?php if(isset($stats['kamael'])) { ?>
        <tr>
            <td>Камаэли</td>
            <td><?php echo $stats['kamael'] ?>%</td>
        </tr>
    <?php } ?>
    <tr>
        <th colspan="2">Рейты</th>
    </tr>
    <?php foreach($rates as $k => $v) { ?>
        <tr>
            <td><?php echo get_normal_name_for_rates($k) ?></td>
            <td><?php echo $v ?></td>
        </tr>
    <?php } ?>
</table>