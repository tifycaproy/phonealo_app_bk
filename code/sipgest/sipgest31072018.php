<?php
//include 'lib/seguridad.php';

$context_menu = array (
    'Inicio' => path('sipgest')
);

$breadcrumb = array (
    'Inicio' => path('siptest'),
    'Dashboard' => '#'
);

//Minutos disponibles.
$min_disponibles = $acu->queryFirstField("select sum(minutes) summinutes from pines where minutes > 1 and fecha_insercion > '2017-01-01' and llamando <> 666");

$response = getapi(fullpath('sip001.call53.com/c53api/api/billinginfo',
    array (
        'APIKEY' => APIKEY,
        'getData' => 'totalcredit'
    )
));

$datCredit = unserialize($response);

$total_credit = euroFormat2form($datCredit['total_credit']);

$minutos_compromiso = euroFormat2form($total_credit / 0.46);

$total_recargas = $db->queryFirstField("select count(pay_cod) from payments where pay_ok = 1 and pay_cod >100");
$total_descargas = $db->queryFirstField("select count(usu_cod) from usuario where usu_cod > 117");