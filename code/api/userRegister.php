<?php
include_once(file_path.'lib/secureAPI.php');
$view = 'json';
$response = array ();

$jsondata = file_get_contents('php://input');
$data = json_decode($jsondata);

if (!is_null($data->{"name"})) {
    $user = new \CompayPhone\usuario();

    /** Esto es un parche hasta que se solucione el problema del registro en ANDROID */
    /*
    if (strpos(strtoupper($data->{'deviceinfo'}), strtoupper('Android')) > 0) {
        $bad_prefix = $data->{'country_prefix'};
        $data->{'country_prefix'} = $data->{'email'};
        $data->{'email'} = $bad_prefix;
    }
    */

    $usu_new = 0;
    $prefix = $data->{'country_prefix'};
    $mobile = $data->{'mobile'};
    $user->loadByMobilePrefix($mobile, $prefix);

    if (count($user->data) == 0) {

        $usu_new = 1;
        $user->data['usu_mobile'] = $data->{'mobile'};
        $user->data['usu_country_prefix'] = $data->{'country_prefix'};
        $user->data['usu_created'] = factual_datetime_mysql();
        $user->setServer(1); // De momento esto de los servers lo ponemos a capon

        $db->insert('log', array (
            'action' => 'Provisiona usuario nuevo: '.$data->{'mobile'},
            'info' => $jsondata
        ));

    } else {

        if ($user->data['usu_status'] == 4) {
            $user->setServer(1); // Si es un registro y está status 4 lo pasa a el servidor 1
            $db->insert('log', array (
                'action' => 'Reasignamos server: '.$data->{'mobile'},
                'info' => $jsondata
            ));
        } else {
            $db->insert('log', array (
                'action' => 'Recupera usuario existente: '.$data->{'mobile'},
                'info' => $jsondata
            ));
        }
        $usu_new = $user->data['usu_new'];
    }

    $user->data['usu_name'] = utf8_decode($data->{'name'});
    $user->data['usu_email'] = $data->{'email'};

    $user->setPIN(); //Fija el pin y lo guarda y lo envía
    /*
     * Aqui las validaciones
     */
    $user->save();
    $user->load();


    $server = new \CompayPhone\serversip($user->data['usu_srv_cod']);
    $server->load();

    // Creamos una nueva asignación para el usuario
    if (strlen($user->data['usu_billing_cardid']) == 0 || $user->data['usu_status'] == 4) {

        $caller_response = $server->getFreeCallerID($user->data);

        if ($caller_response->result == 'ok') {
            $cardd = $caller_response->data;
            $user->data['usu_billing_cardid'] = $cardd->{'id'};
            $user->data['usu_billing_cardusername'] = $cardd->{'username'};
            $user->data['usu_status'] = 1;
            $user->save();
        } else {
            //Controlar el error
            $user->data['usu_status'] = 2;
            $user->save();
        }
        //$user->genExtension();
    }

    $response['account'] = array (
        'id' => $user->data['usu_billing_cardusername'],
        'name' => utf8_encode($user->data['usu_name']),
        'sipserver' => $server->data['srv_name'],
        'port' => $server->data['srv_sipport'],
        'pin' => $user->data['usu_key'],
    );

    $tarifa = new \CompayPhone\tarifas();
    $response['rates'] = $tarifa->getAll();

    $paises = $util->getPaises();

    $response['countries'] = $paises;
    $balance = $user->getBalance();

    $response['balance'] = array (
        array (
            'bal_cod' => '1',
            'bal_usu_cod' => $user->cod,
            'bal_amount' => $balance['credit'],
            'bal_currency' => $balance['currency']
        )
    );

    //$response['balance'] = number_format($balance['credit'], 2);
    $usu_data = $user->data;

    $template = (intval($usu_new) == 1)?'bienvenida0':'bienvenida1';

    $destinatarios = array (
        $usu_data['usu_email']
    );

    if ((strtotime($usu_data['usu_lastnotify'].' + 2 min') < strtotime(factual_datetime_mysql())) || (strlen($usu_data['usu_lastnotify']) == 0)) {
        envia_mail('Bienvenido / Welcome ', $template, $usu_data, $destinatarios );
        $db->update('usuario', array ('usu_lastnotify' => factual_datetime_mysql()), 'usu_cod = '.$usu_data['usu_cod']);
    }

}


