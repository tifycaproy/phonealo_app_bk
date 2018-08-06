<?php
require_once (file_path.'lib/redsys/apiRedsys.php');

//$r = 'a:3:{s:19:"Ds_SignatureVersion";s:14:"HMAC_SHA256_V1";s:12:"Ds_Signature";s:44:"pqQMCPmscpyypQksL7yy0D4KX_DsgzwF-AX1S4UTBQc=";s:21:"Ds_MerchantParameters";s:436:"eyJEc19EYXRlIjoiMzBcLzA0XC8yMDE3IiwiRHNfSG91ciI6IjAxOjIxIiwiRHNfU2VjdXJlUGF5bWVudCI6IjEiLCJEc19DYXJkX0NvdW50cnkiOiI3MjQiLCJEc19BbW91bnQiOiIxMDAwIiwiRHNfQ3VycmVuY3kiOiI5NzgiLCJEc19PcmRlciI6Ijk1MDE2NSIsIkRzX01lcmNoYW50Q29kZSI6IjE3NTExNDgxOCIsIkRzX1Rlcm1pbmFsIjoiMDAxIiwiRHNfUmVzcG9uc2UiOiIwMTg0IiwiRHNfTWVyY2hhbnREYXRhIjoiIiwiRHNfVHJhbnNhY3Rpb25UeXBlIjoiMCIsIkRzX0NvbnN1bWVyTGFuZ3VhZ2UiOiIxIiwiRHNfQXV0aG9yaXNhdGlvbkNvZGUiOiIgICAgICAifQ==";}';
//$r = 'a:3:{s:19:"Ds_SignatureVersion";s:14:"HMAC_SHA256_V1";s:21:"Ds_MerchantParameters";s:488:"eyJEc19EYXRlIjoiMzBcLzA0XC8yMDE3IiwiRHNfSG91ciI6IjE5OjM4IiwiRHNfU2VjdXJlUGF5bWVudCI6IjEiLCJEc19DYXJkX1R5cGUiOiJEIiwiRHNfQ2FyZF9Db3VudHJ5IjoiNzI0IiwiRHNfQW1vdW50IjoiMTAwMCIsIkRzX0N1cnJlbmN5IjoiOTc4IiwiRHNfT3JkZXIiOiIyMDMwMTY3IiwiRHNfTWVyY2hhbnRDb2RlIjoiMTc1MTE0ODE4IiwiRHNfVGVybWluYWwiOiIwMDEiLCJEc19SZXNwb25zZSI6IjAwMDAiLCJEc19NZXJjaGFudERhdGEiOiIiLCJEc19UcmFuc2FjdGlvblR5cGUiOiIwIiwiRHNfQ29uc3VtZXJMYW5ndWFnZSI6IjEiLCJEc19BdXRob3Jpc2F0aW9uQ29kZSI6IjM4NDU4MCIsIkRzX0NhcmRfQnJhbmQiOiIxIn0=";s:12:"Ds_Signature";s:44:"FRFl4KRutEQq6gt_kiSCBl1JD7eTcNf16YC1z7MczMY=";}';
$r = 'a:3:{s:19:"Ds_SignatureVersion";s:14:"HMAC_SHA256_V1";s:21:"Ds_MerchantParameters";s:488:"eyJEc19EYXRlIjoiMzBcLzA0XC8yMDE3IiwiRHNfSG91ciI6IjE5OjU5IiwiRHNfU2VjdXJlUGF5bWVudCI6IjEiLCJEc19DYXJkX1R5cGUiOiJEIiwiRHNfQ2FyZF9Db3VudHJ5IjoiNzI0IiwiRHNfQW1vdW50IjoiMTAwMCIsIkRzX0N1cnJlbmN5IjoiOTc4IiwiRHNfT3JkZXIiOiIxNzAwMTY4IiwiRHNfTWVyY2hhbnRDb2RlIjoiMTc1MTE0ODE4IiwiRHNfVGVybWluYWwiOiIwMDEiLCJEc19SZXNwb25zZSI6IjAwMDAiLCJEc19NZXJjaGFudERhdGEiOiIiLCJEc19UcmFuc2FjdGlvblR5cGUiOiIwIiwiRHNfQ29uc3VtZXJMYW5ndWFnZSI6IjEiLCJEc19BdXRob3Jpc2F0aW9uQ29kZSI6Ijk2NzI4NSIsIkRzX0NhcmRfQnJhbmQiOiIxIn0=";s:12:"Ds_Signature";s:44:"weqCSrXAcwiPCUlGD0sUMko-jTd-Jj_O3XoQR0jEGsA=";}';

$a = unserialize($r);

print_object($a);

$payback = new RedsysAPI;
$kc = '2yKKlrw9UsplPBT3O5aIUfUrEgrZjvs4';//Clave recuperada de CANALES
$MySignature = $payback->createMerchantSignatureNotif($kc, $a['Ds_MerchantParameters']);

if ($MySignature == $a['Ds_Signature']) {
    // La firma es buena
    $decodec = json_decode($payback->decodeMerchantParameters($a['Ds_MerchantParameters']));
    $pedidotpv = $payback->getParameter('Ds_Order');
    $pay_data = $db->queryFirstRow('select * from payments where pay_pedidotpv = %s', $pedidotpv);
    print_object($pay_data);
    $response = (int) $payback->getParameter('Ds_Response');
    if (($response >= 0 and $response <= 99) or ($response == 900) or ($response == 400) ) {


    } else {

    }

    print_object('Respuesta:'.$response);
    echo 'Pedido:'.$payback->getParameter('Ds_Order');
}


die(0);