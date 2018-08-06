<?php
/**
 * Created by PhpStorm.
 * User: alx
 * Date: 29/6/17
 * Time: 12:06
 */

namespace CompayPhone;


class ezetop {

    //var $auth = 'AuthorizatN0UyMzZBNUFGNzM4NDA5QUFCREEwMjA3N0ZFNEFEQTI6NlFZWlkzTkFIV0dONEJYVUJDVkZWQVNCRlJLRUJBRVY=';
    var $auth = 'Authorization: Basic N0UyMzZBNUFGNzM4NDA5QUFCREEwMjA3N0ZFNEFEQTI6NlFZWlkzTkFIV0dONEJYVUJDVkZWQVNCRlJLRUJBRVY=';
    var $url = 'https://edts.ezedistributor.com/api/EdtsV3/';

    function get($service, $data_string = '') {

        //$data_string = eregi_replace("[\n|\r|\n\r]", ' ', $data_string);
        $data_string = preg_replace("/[\n|\r|\n\r]/", ' ', $data_string);

        $ch = curl_init() or die(curl_error($ch));
        curl_setopt($ch, CURLOPT_URL, $this->url.$service);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array (
            'Accept: application/json',
            $this->auth
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $data1 = curl_exec($ch);

        /*
        $info = curl_getinfo($ch);
        print_object($info);
        */

        curl_close($ch);

        return $data1;
    }



    /*
     * Hace una llamada POST a la API de EZETOP
     */
    function post($service, $fields) {

        $ch = curl_init($this->url.$service);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array (
            'Content-Type: application/json',
            'Accept: application/json',
            $this->auth
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        //$info = curl_getinfo($ch);
        //print_object($info);

        curl_close($ch);
        //conv_log($url . ' <br/>' . $result, 'Resultado envio datos');
        return $result;

    }

} 