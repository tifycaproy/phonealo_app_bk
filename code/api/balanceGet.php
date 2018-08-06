<?php
include_once(file_path.'lib/secureAPI.php');
$view = 'json';
$response = array ();

$jsondata = file_get_contents('php://input');
$data = json_decode($jsondata);

$db->insert('log', array (
    'action' => 'Balance get : '.$data->{'mobile'},
    'info' => $jsondata
));


if (!is_null($data->{"account_id"})) {

    $account_id = $data->{"account_id"};
    $usuario = new \CompayPhone\usuario();
    $usuario->loadById($account_id);

    $balance = $usuario->getBalance();

    //Hay que ver si tiene refacturacion pendiente

    $usuario->db->insert('mylog', array (
        'log' => 'Id de usuario : '.$data->{"account_id"},
        'momento' => factual_datetime_mysql()
    ));

    $response['balance'] = array (
        array (
            'bal_cod' => '1',
            'bal_usu_cod' => $usuario->cod,
            'bal_amount' => $balance['credit'],
            'bal_currency' => $balance['currency']
        )
    );

    $response['url_payment'] = fullpath('app.phonealo.net/payment/init',
        array ('mobile' => $usuario->data['usu_mobile'], 'country' => $usuario->data['usu_country_prefix'], 'amount' => 10));

    $tarifa = new \CompayPhone\tarifas();
    $response['rates'] = $tarifa->getAll();

    $paises = $util->getPaises();

    $response['countries'] = $paises;

    $db->insert('log', array (
        'action' => 'Resumen Balance : '.$data->{'mobile'},
        'info' => json_encode($response)
    ));


}
