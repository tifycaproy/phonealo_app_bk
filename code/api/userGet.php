<?php
include_once(file_path.'lib/secureAPI.php');
$view = 'json';
$response = array ();

if (ppost('name')) {
    $user = new \CompayPhone\usuario();

    $server = new \CompayPhone\serversip(2);
    $server->load();

    $extension = new \CompayPhone\extension();
    $extension->data['ext_usu_cod'] = $user->cod;
    $extension->data['ext_pin'] = $user->data['usu_key'];
    $extension->data['ext_pin'] = $user->data['usu_key'];
    $extension->load();

    /*
    $response['account'] = array (
        'id' => $user->data['usu_country_prefix'].$user->data['usu_mobile'],
        'name' => utf8_encode($user->data['usu_name']),
        'sipserver' => $server->data['srv_name'],
        'secret' => $user->data['usu_key']
    );
    */
    $response['account'] = array (
        'id' => '0034666777801',
        'name' => utf8_encode($user->data['usu_name']),
        'sipserver' => $server->data['srv_name'],
        'secret' => '1234'
    );

    $tarifa = new \CompayPhone\tarifas();
    $response['rates'] = $tarifa->getAll();

    //$response['paises'] = $db->query("select pais_country_prefix, pais_desc from paises where pais_active = 1");
    $paises = $util->getPaises();
    $response['countries'] = $paises;

    $balance = $db->query('select * from balance');
    $response['balance'] = $balance;

}


