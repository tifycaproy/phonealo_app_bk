<?php
include_once(file_path.'lib/secureAPI.php');
$view = 'json';
$response = array ();

$jsondata = file_get_contents('php://input');
$data = json_decode($jsondata);


if (!is_null(pget("cardid"))) {

    $user = new \CompayPhone\usuario();
    $cardid = pget('cardid');
    $user->loadByCardId($cardid);

    if (count($user->data) > 0) {

        $template = pget('template');

        $usu_data = $user->data;
        $destinatarios = array (
            $usu_data['usu_email']
        );
        $usu_data['pay_amount'] = pget('pay_amount');

        envia_mail('Recarga promocional CALL53', $template, $usu_data, $destinatarios );

    }

}
