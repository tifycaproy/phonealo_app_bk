<?php

if (ppost('mobile') && strlen(ppost('mobile')) > 0) {

    $usu = new \CompayPhone\usuario();
    if (ppost('remember')) {
        $usu->loadByMobilePrefix(ppost('mobile'), ppost('country'));
        if ($usu->cod == 0) {
            set_error('El número de móvil no está registrado para el pais seleccionado');
        } else {
            $template = 'rememberpin';

            $usu_data = $usu->data;
            $destinatarios = array (
                $usu_data['usu_email']
            );

            $usu_data = $usu->data;
            envia_mail('Recordatorio de PIN ', $template, $usu_data, $destinatarios );

            set_message('Hemos enviado el pin a la dirección de email utilizada para el registro ');

        }
    } else {
        $usu->loadByMPPIN(ppost('mobile'), ppost('country'), ppost('pin') );

        if ($usu->cod == 0) {
            set_error('Compruebe su país, teléfono y pin y vuelva a intentarlo');
        } else {
            $balance = $usu->getBalance();

            //Hay que ver si tiene refacturacion pendiente
            $view = 'details';
        }
    }

}

