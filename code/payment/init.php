<?php
require_once (file_path.'lib/redsys/apiRedsys.php');

$view = 'recarga';

$db->insert('log', array (
    'action' => 'Se recibe post ',
    'info' => serialize($_POST)
));

if (isset($_POST['Ds_SignatureVersion'])) {

    $db->insert('log', array (
        'action' => 'Retorno de pago REDSYS',
        'info' => serialize($_POST)
    ));

    $p = $_POST;
    $payback = new RedsysAPI;
    $kc = '2yKKlrw9UsplPBT3O5aIUfUrEgrZjvs4'; //Clave recuperada de CANALES
    $MySignature = $payback->createMerchantSignatureNotif($kc, $p['Ds_MerchantParameters']);

    if ($MySignature == $p['Ds_Signature']) {
        // La firma es buena
        $decodec = json_decode($payback->decodeMerchantParameters($p['Ds_MerchantParameters']));
        $pedidotpv = $payback->getParameter('Ds_Order');
        $pay_data = $db->queryFirstRow('select * from payments where pay_pedidotpv = %s', $pedidotpv);
        $usu = new \CompayPhone\usuario($pay_data['pay_usu_cod']);
        $usu->load();
        $usu_data = $usu->data;

        $response = (int) $payback->getParameter('Ds_Response');
        if (($response >= 0 and $response <= 99) or ($response == 900) or ($response == 400) ) {
            $response = $usu->impute_saldo($pay_data['pay_amount'], $pay_data['pay_cod']);
            if ($usu_data['usu_new'] == 1) {
                $response = $usu->impute_saldo($pay_data['pay_amount'], $pay_data['pay_cod']);
                $db->update('usuario', array
                    (
                        'usu_new' => 0
                    ),
                    'usu_cod = '.$usu_data['usu_cod']
                );

            }
            $decoded = json_decode($response);

            if ($decoded->{credit}) {


                $db->update('payments',
                    array
                    (
                        'pay_ok' => 1,
                        'pay_notified' => factual_datetime_mysql(),
                        'pay_tpvresponse' => (int) $payback->getParameter('Ds_Response')

                    ),'pay_cod = %s', $pay_data['pay_cod']);
                $usu_data = $usu->data;
                if ($usu_data['usu_new'] == 1) {
                    $template = 'okrecargadoble';
                } else {
                    $template = 'okrecarga';
                }
                $destinatarios = array (
                    $usu_data['usu_email']
                );
                $usu_data['credit'] = number_format($decoded->{credit}, 2);
                $usu_data['pay_amount'] = $pay_data['pay_amount'];
                envia_mail('Todo Ok, ya puedes llamar con CALL53 ! ', $template, $usu_data, $destinatarios );
            }

        } else {

            $db->update('payments',
                array
                (
                    'pay_ok' => 0,
                    'pay_notified' => factual_datetime_mysql(),
                    'pay_tpvresponse' => (int) $payback->getParameter('Ds_Response')

                ),'pay_cod = %s', $pay_data['pay_cod']);


            $template = 'fallorecarga';
            $destinatarios = array (
                $usu_data['usu_email']
            );

            envia_mail('Ups, no hemos podido recargar tu cuenta :-( ', $template, $usu_data, $destinatarios );

        }

    }

    die(0); //Aqui cerramos el proceso, no damos respuesta a REDSYS

}

if (pget('faildata') && is_null(ppost('mobile'))) {

    $shaID = MyDeCriptSHA1(substr(pget('faildata'), 0 , -1));
    $pay_data = $db->queryFirstRow('select * from payments where sha1(pay_cod) = %s', $shaID);

    $usu = new \CompayPhone\usuario($pay_data['pay_usu_cod']);
    $usu->load();

    $mobile = $usu->data['usu_mobile'];
    $country = $usu->data['usu_country_prefix'];
    $amount = $pay_data['pay_amount'];

    set_error('No se ha procesado la recarga, consulta con tu banco o prueba con otra tarjeta');

} elseif (!is_null(ppost('mobile'))) {

    $mobile = ppost('mobile');
    $country = ppost('country');
    $amount = ppost('amount');

    if (strlen($mobile) == 0)
        set_error('Debe introducir un número de teléfono');

    if ($country == 0)
        set_error('Seleccione el pais asociado al registro de su teléfono');

    if ($amount == 0)
        set_error('Seleccione un importe de recarga');

    if (count_errors() == 0) {

        $usu_data = $db->queryFirstRow(
            'select * from usuario where usu_mobile like %s and usu_country_prefix like %s',
            $mobile, $country
        );

        $card_id = $usu_data['usu_billing_cardid'];
        $card_secure_id = MyEncriptSHA1($card_id);

        if (!is_null($card_id)) {

            $db->insert('payments', array (
                'pay_usu_cod' => $usu_data['usu_cod'],
                'pay_timestamp' => substr($card_secure_id, 40),
                'pay_amount' => $amount
            ));

            $pay_cod = $db->insertId();

            $pedidotpv =  $usu_data['usu_cod'].'0'.$pay_cod;
            $db->update('payments', array ('pay_pedidotpv' => $pedidotpv), 'pay_cod = %s', $pay_cod);

            $secure_id = ppost('payid');
            $secure_pay_id = MyEncriptSHA1($pay_cod);
            $view = 'do';

        } else {
            set_error('El teléfono no está registrado en CALL53');
        }

    }


} else {
    $amount = pget('amount');
    $mobile = pget('mobile');
    $country = pget('country');

}
