<html>
<body>
<a href='ec-cedula?gen=do&n=<?= $max;?>'>Generar <?= $max;?> m&aacute;s</a>
<form method="post">
    <?php
    $tablecel = new html_table('tc', $res_cel, 'cel_cod');
    $tablecel->add_column('num', 'Cedula', '{cel_num}');
    $tablecel->add_column('year', 'Año', '<input name="year[{cel_cod}]" value="{cel_year}" size="6">');
    $tablecel->add_column('ok', 'Buena', 'mala[{cel_cod}],{cel_ok}', '', 'dropdown_SINO_01');
    echo $tablecel->print_table();
    ?>
    <input type="submit" value="Guardar" style="padding: 5px 5px 5px 5px; font-weight: bold">
</form>
<?php
    $reslotes = $db->query('select min(cel_lote) lote, min(cel_lote_fecha) fecha from ec_cedulas where cel_lote_fecha is not null group by cel_lote order by cel_lote desc limit 50');
    $lotet = new html_table('tl', $reslotes, 'cel_lote');
    $lotet->add_column('lote', 'Lote', '{fecha}', '', 'cambiaf_a_normal_time');
    $lotet->add_column('link', 'Ver', '<a href="?lote={lote}">Ir ...</a>');
    echo $lotet->print_table()
?>
</body>
</html>