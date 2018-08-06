<?php

/**
 * Created by PhpStorm.
 * User: alx
 * Date: 21/03/15
 * Time: 12:01
 */

namespace CompayPhone;

class serversip extends base {

    function __construct($srv_cod = 0, $db = null) {

        parent::__construct($srv_cod, $db);
        $this->table_name = 'sipservers';
        $this->name_cod = 'srv_cod';
        $this->cod = $srv_cod;
    }

    /*
     * Aqui creamos la extension en el servidor con el pin que le corresponde
     */
    function createInServer() {

    }

    /*
     * Cargamos o asignamos el caller id disponible
     */
    function getFreeCallerID($usu_data) {

        /*
        echo fullpath($this->data['srv_name'].'/c53api/api/getcaller',
            array (
                'APIKEY' => APIKEY,
                'prefix' => $usu_data['usu_country_prefix'],
                'mobile' => $usu_data['usu_mobile'],
                'group' => $this->data['srv_groupapp']
            )
        );die(0);
        */

        $response = getapi(fullpath($this->data['srv_name'].'/c53api/api/getcaller',
            array (
                'APIKEY' => APIKEY,
                'prefix' => $usu_data['usu_country_prefix'],
                'mobile' => $usu_data['usu_mobile'],
                'group' => $this->data['srv_groupapp'],
                'lastname' => $usu_data['usu_name']
            )
        ));

        return json_decode($response);

    }

}

