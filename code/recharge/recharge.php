<?php
if (have_value(ppost('inisession')) && ppost('inisession') == 'PorMisPerras-17') {
    $_SESSION['recargador'] = 'recargando';
    redirect('recharge');
}

if (is_null($_SESSION['recargador']) && $_SESSION['recargador'] != 'recargando') {
    $view = 'inisession';
} else {

    if (have_value(pget('countrie'))) {
        $countrie = pget('countrie');
        if ($countrie == 'CU') {
            $countriename = 'CUBA';
        } else {
            redirect('recharge');
        }
    } else {
        $countrie = 'EC';
        $countriename = 'ECUADOR';
    }

    $ezetop = new \CompayPhone\ezetop();
    //print_object($balance);

    $providers_eze = json_decode($ezetop->get('GetProviders?countryIsos='.$countrie));

    //print_object($providers_eze);
    $providers = array ();
    foreach ($providers_eze->Items as $item) {
        $providers[$item->ProviderCode] = $item->Name;
    }
    $products_ec = json_decode($ezetop->get('GetProducts?countryIsos='.$countrie));
    $promotions_ec = json_decode($ezetop->get('GetPromotions?countryIsos='.$countrie.'&providerCodes=CU_CU_TopUp'));

    //print_object($promotions_ec);
    //print_object($products_ec); die(0);

    //$products_ec = json_decode($ezetop->get('GetPromotions?countryIsos=EC'));
    //print_object($ezetop->get('GetProductDescriptions?skuCodes=EC_ME_TopUp'));

    $items = $products_ec->Items;
    $productos = array ();
    $info_recargas = array ();
    foreach ($items as $item) {
        //print_object($item);
        $productos[$item->SkuCode] = $providers[$item->ProviderCode];
        $info_recargas[] = $item->SkuCode.' - '.$providers[$item->ProviderCode].' desde: '.$item->Minimum->SendValue.' a '.$item->Maximum->SendValue.' '.$item->Maximum->SendCurrencyIso.' TEST:'.$item->UatNumber;
    }

    if (have_value(ppost('sku'))) {

        $s = str_replace("&#13;&#10;", "<BR>", ppost('numbers'));
        $anumbers = explode('<BR>', $s);
        $info_response = array ();

        if (ppost('importe') > 0 && count($anumbers) > 0) {
            foreach ($anumbers as $number2refill) {

                if (have_value($number2refill)) {
                    $data_recharge = array (
                        'SkuCode' => ppost('sku'),
                        'SendValue' => ppost('importe'),
                        'AccountNumber' => $number2refill,
                        'DistributorRef' => time(),
                        'ValidateOnly' => false
                    );
                    $response = json_decode($ezetop->post('SendTransfer', json_encode($data_recharge)));

                    //print_object($response);

                    if ($response->ResultCode == 1 and $response->TransferRecord->TransferId->TransferRef <> 0) {
                        $info_response[] = $number2refill.' OK '.$response->TransferRecord->TransferId->TransferRef;
                    } else {
                        $info_response[] = $number2refill.' <span style="text-color=red;">FALLO!! '.$response->TransferRecord->TransferId->DistributorRef.'</span>';
                    }

                    sleep(2); //Nos esperamos 2 segundos para la siguiente llamada
                }

            }

        } else {
            $info_response[] = ' <span style="color=red;">FALLO!! No hay importe para recarga o no hay números </span>';
        }

    }

    $balance = json_decode($ezetop->get('GetBalance'));

}

