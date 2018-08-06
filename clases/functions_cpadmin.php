<?php

function showASR($total, $contestadas) {
    return number_format($contestadas/$total * 100, 2);
}

function showACD($segundos, $contestadas) {
    return number_format(showMinutos($segundos) / $contestadas ,2);
}

/*
 * Con esto devolvemos un falso SHA1, util para URLS publicas
 */
function MyEncriptSHA1($value){
    $vsecure = sha1($value);
    $char_pos = date('j');
    return(substr($vsecure, 0, $char_pos).
        substr($vsecure, $char_pos + 1).
        substr($vsecure, $char_pos, 1).time());
}

function MyDeCriptSHA1($value){
    $vsecure = substr($value, 0, 40);
    $rtime = substr($value, -10);
    $char_pos = gmdate('j', $rtime);
    $vsearch = substr($vsecure, 0, $char_pos).substr($vsecure, -1, 1).substr($vsecure, $char_pos, -1);
    return $vsearch;
}

//Options con la lista de paises de los clientes registrados
function dropDownPaisCliente($prefijo) {
    $a = array (
        '0' => 'Seleccione ...'
    );
    global $util;
    $paises = $util->getPaises();
    foreach ($paises as $pais) {
        print_object($pais);
        $a[$pais['country_prefix']] = $pais['country_name'];
    }

    return dropdownGeneric('importes', $a, $prefijo, '', true);
}

function dropDownImportesRecarga ($importe) {
    $a = array (
        0 => 'Seleccione ...',
        //1 => '1 €',
        5 => '5 €',
        10 => '10 €',
        20 => '20 €',
        50 => '50 €',
        100 => '100 €',
        500 => '500 €',
    );
    return dropdownGeneric('importes', $a, $importe, '', true);
}

function envia_mail($subject, $mail_template, $data_mail, $destinatarios = 0, $files = null, $usu = null) {

    $mail = new \PHPMailer();
    $mail->PluginDir = file_path."lib/mailer/";
    $mail->Mailer = "smtp";

    $mail->IsHTML(true);
    $mail->SMTPAuth = true;
    $mail->isSMTP();

    $mail->FromName = 'Call53 la APP para llamar a Cuba';
    //$mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.call53.com';
    $mail->Username = 'call53@call53.com';
    $mail->Password = 'Quericapera-16';
    $mail->Port = '25';
    //$mail->Port = '587';

    $mail->From = 'call53@call53.com';

    if (is_array($destinatarios)) {
        foreach ($destinatarios as $address) {
            $mail->AddAddress($address);
        }
    }

    //$mail->addCC('alexcruzruiz@gmail.com');
    $mail->addBCC('gestion.mam@gmail.com');
    //$mail->addBCC('alexcruzruiz@gmail.com');

    if (is_array($files)) {
        foreach ($files as $filepath) {
            $mail->addAttachment($filepath);
        }
    }

    $mail->Subject = $subject;
    $fs = filesize(file_path.'theme/mail_tpl/'.$mail_template.'.html');
    $file_mail = fopen(file_path.'theme/mail_tpl/'.$mail_template.'.html',"rb");

    $mail_body = fread($file_mail, $fs);
    $mail->Body = parseTemplate($mail_body, $data_mail);

    //$mail->SMTPDebug = 2;

    return $mail->Send();

}
