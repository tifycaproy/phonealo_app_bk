<?php
include_once(file_path.'lib/secureAPI.php');
$view = 'json';
$response = array ();

$jsondata = file_get_contents('php://input');
$data = json_decode($jsondata);

$tarifa = new \CompayPhone\tarifas();
$response['rates'] = $tarifa->getAll();

$paises = $util->getPaises();
$response['countries'] = $paises;

if (!is_null($data->{"account_id"})) {

    $usuario = new \CompayPhone\usuario();
    $usuario->loadById(pget('account_id'));

    $balance = $db->query('select * from balance');

    $response['balance'] = $balance;

}