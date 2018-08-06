<?php

if (pget('action') == 'loadbymobile') {
    $usu = new \CompayPhone\usuario();
    $usu->loadByMobilePrefix(pget('mobile'), pget('countryprefix'));
    if ($usu->cod > 0) {
        $response['uname'] = $usu->data['usu_name'];
    } else {
        $response['uname'] = 'nouser';
    }
    print json_encode($response);
}


if (ppost('clave')) {
    $age_cod = $db->queryOneField('age_cod',
        "SELECT * FROM agentes WHERE age_login=%s and age_pass=%s", ppost('agent'), ppost('clave'));

    if (strlen($age_cod) > 0) {
        $_SESSION['age'] = $age_cod;
        $response = array (
            'login' => 'ok'
        );
    } else {
        $response = array (
            'login' => 'ko'
        );
    }

    print json_encode($response);
}


die(0);