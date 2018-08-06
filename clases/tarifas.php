<?php

/**
 * Created by PhpStorm.
 * User: alx
 * Date: 21/03/15
 * Time: 12:01
 */

namespace CompayPhone;

class tarifas extends base {

    function __construct($tar_cod = 0, $db = null) {

        parent::__construct($tar_cod, $db);
        $this->table_name = 'tarifas';
        $this->name_cod = 'tar_cod';
        $this->cod = $tar_cod;
    }

    function getAll() {

        $data = $this->db->query('select tar_cod, tar_country_prefix, pais_desc as tar_country_name, tar_price, tar_currency, tar_created, tar_active
              from tarifas, paises where tar_pais_cod = pais_cod and tar_active = 1');

        $ret = array ();
        foreach ($data as $tar) {
            $tar['tar_country_name'] = utf8_encode($tar['tar_country_name']);
            $ret[] = $tar;
        }

        return $ret;

    }

}