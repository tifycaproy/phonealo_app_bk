<?php
require_once (file_path.'lib/redsys/apiRedsys.php');

if (pget('failed')) {
    set_error('No se ha procesado la recarga, consulta con tu banco o prueba con otra tarjeta');
    redirect('recharge/cubacel');
}
if (pget('process')) {
    set_message('PERFECTO! <br>Hemos procesado la recarga correctamente');
    redirect('recharge/cubacel');
}

if (pget('quit')) {
    unset($_SESSION['age']);
}

if (isset($_SESSION['age']) && $_SESSION['age'] > 0) {
    $age_data = $db->queryFirstRow('select age_cod, age_nombre, age_saldo from agentes where age_cod = %s', $_SESSION['age']);
} else {
    $age_data = null;
}

$js_page = array (
    "js/recargas_cubacel.js"
);

$factual = factual_dateYMD();
$ezetop = new \CompayPhone\ezetop();

$a_servicios = $db->query('select * from reccu_servicios where %s >= servi_fecha_ini and %s <= servi_fecha_fin',
    $factual, $factual
    );

$view = 'cubacel_init';

//if (isset($_POST['Ds_SignatureVersion'])) {
$p = $_POST;
//$p = $_GET; //El GET lo usamos para pruebas

if (isset($p['Ds_SignatureVersion'])) {

    $db->insert('log', array (
        'action' => 'Retorno CUBACEL de pago REDSYS',
        'info' => serialize($_POST)
    ));

    $payback = new RedsysAPI;
    $kc = '2yKKlrw9UsplPBT3O5aIUfUrEgrZjvs4'; //Clave recuperada de CANALES
    $MySignature = $payback->createMerchantSignatureNotif($kc, $p['Ds_MerchantParameters']);

    if ($MySignature == $p['Ds_Signature']) {
        // La firma es buena
        $decodec = json_decode($payback->decodeMerchantParameters($p['Ds_MerchantParameters']));
        $pedidotpv = $payback->getParameter('Ds_Order');
        $pay_data = $db->queryFirstRow('select * from reccu_payments where recpay_pedidotpv = %s', $pedidotpv);

        if ($pay_data['recpay_usu_cod'] > 0) {
            $usu = new \CompayPhone\usuario($pay_data['recpay_usu_cod']);
            $usu->load();
            $usu_data = $usu->data;
        }

        $response_pay = (int) $payback->getParameter('Ds_Response');
        if (($response_pay >= 0 and $response_pay <= 99) or ($response_pay == 900) or ($response_pay == 400) ) {
        //if ( true ) {

            $service = $db->queryFirstRow('select * from reccu_servicios where servi_cod = %s',
                $pay_data['recpay_service_cod']);

            $data_recharge = array (
                'SkuCode' => $service['servi_sku'],
                'SendValue' => $pay_data['recpay_amount'],
                'AccountNumber' => $pay_data['recpay_mobilecu'],
                'DistributorRef' => $pay_data['recpay_pedidotpv'],
                'ValidateOnly' => true
            );

            $response = json_decode($ezetop->post('SendTransfer', json_encode($data_recharge)));

            //print_object($response_pay);
            if ($response_pay->ResultCode == 1 and $response_pay->TransferRecord->TransferId->TransferRef <> 0) {
            //if ($response->ResultCode == 1 ) {
                $info_response[] = $number2refill.' OK '.$response->TransferRecord->TransferId->TransferRef;

                $data_confirma = array (
                    'servi_info' => $service['servi_info'],
                    'recpay_amount' => $pay_data['recpay_amount'],
                    'recpay_mobilecu' => $pay_data['recpay_mobilecu'],
                    'recpay_pedidotpv' => $pay_data['recpay_pedidotpv']
                );

                $destinatarios_cu = array (
                    'recpay_correoe' => $pay_data['recpay_correoe']
                );

                envia_mail('RECARGA CUBACEL COMPLETADA ', 'confirmacubacel', $data_confirma, $destinatarios_cu );

                $db->insert('log', array (
                    'action' => 'RECARGA CUBACEL - '.$pay_data['recpay_pedidotpv'].' PROCESADA OK',
                    'info' => serialize($_POST)
                ));

                $db->update('reccu_payments',
                    array
                    (
                        'recpay_pagado' => 1,
                        'recpay_date_pay' => factual_datetime_mysql()
                    ),'recpay_cod = %s', $pay_data['recpay_cod']);



                if ($pay_data['recpay_usu_cod'] > 0 && $usu->cod > 0) {

                    $response_saldo = $usu->impute_saldo(0.92, $pay_data['recpay_pedidotpv']);
                    $decoded = json_decode($response_saldo);

                    if ($decoded->{credit}) {
                        $usu_data = $usu->data;
                        $template = 'okrecargacubacel2m';
                        $destinatarios = array (
                            $usu_data['usu_email']
                        );
                        $usu_data['credit'] = number_format($decoded->{credit}, 2);
                        $usu_data['pay_amount'] = $pay_data['pay_amount'];
                        envia_mail('Saldos para 2 minutos de llamadas a CUBA ', $template, $usu_data, $destinatarios );
                    }
                }

            } else {

                $db->update('reccu_payments',
                    array
                    (
                        'recpay_pagado' => 2,
                        'recpay_date_pay' => factual_datetime_mysql()
                    ),'recpay_cod = %s', $pay_data['recpay_cod']);

                $data_confirma = array (
                    'servi_info' => $service['servi_info'],
                    'recpay_amount' => $pay_data['recpay_amount'],
                    'recpay_mobilecu' => $pay_data['recpay_mobilecu'],
                    'recpay_pedidotpv' => $pay_data['recpay_pedidotpv'],                    ''
                );

                $destinatarios_cu = array (
                    'recpay_correoe' => $pay_data['recpay_correoe']
                );
                envia_mail('FALLO EN LA RECARGA CUBACEL ', 'confirmacubacel', $data_confirma, $desinatarios_cu );
                $info_response[] = $number2refill.' <span style="text-color=red;">FALLO!! '.$response->TransferRecord->TransferId->DistributorRef.'</span>';
            }


        } else {

            $db->insert('log', array (
                'action' => 'CUBACEL FALLO EN PAGO REDSYS',
                'info' => serialize($_POST)
            ));


            $db->update('reccu_payments',
                array
                (
                    'recpay_pagado' => 3, // Identificamos el que no se realiza por cosa del TPV
                    'recpay_date_pay' => factual_datetime_mysql()
                ),'recpay_cod = %s', $pay_data['recpay_cod']);

            $template = 'fallocubacel';
            $destinatarios = array ('alexcruzruiz@gmail.com');
            envia_mail('FALLO DE RECARGA CUBACEL ', $template, $pay_data, $destinatarios );
        }

    }
    die(0); //Aqui cerramos el proceso, no damos respuesta a REDSYS
}


if (strlen(ppost('correoe')) > 0) {

    $balance = json_decode($ezetop->get('GetBalance'));
    $usu = new \CompayPhone\usuario();
    $usu->loadByMobilePrefix(ppost('mobile'), ppost('country'));

    $datarecpay = array (
        'recpay_date_reg' => factual_datetime_mysql(),
        'recpay_correoe' => ppost('correoe'),
        'recpay_agent_cod' => (count($age_data) > 0)?$age_data['age_cod']:0,
        'recpay_service_cod' => ppost('service'),
        'recpay_amount' => ppost('pricerecarga'),
        'recpay_mobilecu' => ppost('mobilecu'),
        'recpay_usu_cod' => ($usu->cod > 0)?$usu->cod:0,
    );
    $db->insert('reccu_payments', $datarecpay);
    $reccid = $db->insertId();

    if ( doubleval($balance->Balance) > ppost('pricerecarga')) {
        $service = $db->queryFirstRow('select * from reccu_servicios where servi_cod = %s', ppost('service'));
        $pedidotpv = $reccid + 72012;
        $db->update('reccu_payments', array ('recpay_pedidotpv' => $pedidotpv), 'recpay_cod = %s', $reccid);
        $view = 'dopay';
    } else {
        $destinatarios = array (
            'alexcruzruiz@gmail.com','gestion.mam@gmail.com'
        );
        envia_mail('Pedido no procesado', 'sinsaldoezetop', $datarecpay, $destinatarios );
        $db->update('reccu_payments', array ('recpay_pagado' => 2), 'recpay_cod = %s', $reccid);
        set_error('En estos momentos es imposible procesar la recarga');
        redirect('recharge/cubacel');
    }

}