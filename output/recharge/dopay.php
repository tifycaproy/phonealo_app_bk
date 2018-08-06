<?php
require_once (file_path.'lib/redsys/apiRedsys.php');
// Se incluye la librería
//include 'apiRedsys.php';
// Se crea Objeto
$miObj = new RedsysAPI;

// Valores de entrada
$fuc="175114818";
$terminal="1";
$moneda="978";
$trans="0";
$secure_pay_id = MyEncriptSHA1($reccid);

$url="https://app.phonealo.net/recharge/cubacel";
$urlOK="https://app.phonealo.net/recharge/cubacel/done?process=".$secure_pay_id.'1';
$urlKO="https://app.phonealo.net/recharge/cubacel/fail?failed=".$secure_pay_id.'0';
//$id=time();
$id= $pedidotpv;
$amount= $datarecpay['recpay_amount'] * 100;

// -Clave de comervio L935PkylcJZxWh6RWbz5
// Clave 256 : 2yKKlrw9UsplPBT3O5aIUfUrEgrZjvs4

// Se Rellenan los campos
$miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
//$miObj->setParameter("DS_MERCHANT_ORDER",strval($id).$card_id);
$miObj->setParameter("DS_MERCHANT_ORDER",strval($id));
//$miObj->setParameter("DS_MERCHANT_ORDER",date('y').$card_id.'.'.strval($id));
//$miObj->setParameter("DS_MERCHANT_ORDER",strval($id));
$miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
$miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
$miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
$miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
$miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url);
$miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);
$miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);

//Datos de configuración
$version="HMAC_SHA256_V1";
$kc = '2yKKlrw9UsplPBT3O5aIUfUrEgrZjvs4';//Clave recuperada de CANALES
// Se generan los parámetros de la petición
$request = "";
$params = $miObj->createMerchantParameters();
$signature = $miObj->createMerchantSignature($kc);

$db->insert('log-recarga', array (
    'action' => 'Intento de RECARGA : '.$reccid,
    'info' => 'Orden: '.serialize($datarecpay),
    'fecha' => factual_datetime_mysql()
));

?>
<?php if ($reccid > 0): ?>
    <html lang="es">
    <head>
    </head>
    <body onload="document.frm.submit()">

    <h3>
        Conectando con nuestro banco ...
    </h3>
    <div style="display: none">
        <form name="frm" action="https://sis.redsys.es/sis/realizarPago" method="POST" >
            Ds_Merchant_SignatureVersion <input type="text" name="Ds_SignatureVersion" value="<?php echo $version; ?>"/></br>
            Ds_Merchant_MerchantParameters <input type="text" name="Ds_MerchantParameters" value="<?php echo $params; ?>"/></br>
            Ds_Merchant_Signature <input type="text" name="Ds_Signature" value="<?php echo $signature; ?>"/></br>
            <input type="submit" value="Enviar" >
        </form>
    </div>

    </body>
    </html>
<?php endif ;?>
