<?php

//https://app.call53.net//payment/init?faildata=c2548064eaf018a1d481dfb9900c93eb7bdef13f15112809070


if (pget('process') && substr(pget('process'), -1) == '1') {

    $shaID = MyDeCriptSHA1(substr(pget('process'), 0 , -1));
    //$db->debugMode(true);

    $pay_data = $db->queryFirstRow('select * from payments where pay_ok = 1 and  sha1(pay_cod) = %s', $shaID);
    //$pay_data = $db->queryFirstRow('select * from payments where pay_ok = 0 and  sha1(pay_cod) = %s', $shaID);
    set_message('PERFECTO! <br>Hemos recibido la recarga correctamente, abre tu aplicación y llama a CUBA');

    if (count($pay_data) == 0 ) {
        set_error('Esta recarga ya ha sido procesada');
        redirect('payment/init');
    }


}

$view = 'recarga';
