<?php

/*
$template = 'nuevousuario';

$destinatarios = array (
    'gestion.mam@gmail.com',
    'alexcruzruiz@gmail.com'
);

envia_mail('Bienvenido / Welcome ', $template, $usu_data, $destinatarios );

*/

$user = new \CompayPhone\usuario(95);
$user->load();
$usu_data = $user->data;

$template = 'bienvenida';

$destinatarios = array (
    $usu_data['usu_email']
);

envia_mail('Bienvenido / Welcome ', $template, $usu_data, $destinatarios );