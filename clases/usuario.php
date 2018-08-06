<?php

/**
 * Created by PhpStorm.
 * User: alx
 * Date: 21/03/15
 * Time: 12:01
 */

namespace CompayPhone;

class usuario extends base {

    function __construct($usu_cod = 0, $db = null) {

        parent::__construct($usu_cod, $db);
        $this->table_name = 'usuario';
        $this->name_cod = 'usu_cod';
        $this->cod = $usu_cod;
    }


    function full_name() {
        return $this->usu_cod . ' - ' . $this->usu_nombre . ' ' . $this->usu_apellidos;
    }


    /**
     * Devolveremos el saldo del cliente
     */
    function getBalance() {

        $server = new \CompayPhone\serversip($this->data['usu_srv_cod']);
        $server->load();

        $this->db->insert('mylog', array (
            'log' => fullpath($server->data['srv_name'].'/c53api/api/getbalance',
                array (
                    'APIKEY' => APIKEY,
                    'card2query' => $this->data['usu_billing_cardusername'],
                )
            ),
            'momento' => factual_datetime_mysql()
        ));

        $response = getapi(fullpath($server->data['srv_name'].'/c53api/api/getbalance',
            array (
                'APIKEY' => APIKEY,
                'card2query' => $this->data['usu_billing_cardusername'],
            )
        ));

        $result = unserialize($response);

        return $result;

    }

    function getcallhistory() {
        $server = new \CompayPhone\serversip($this->data['usu_srv_cod']);
        $server->load();

        $this->db->insert('mylog', array (
            'log' => fullpath($server->data['srv_name'].'/c53api/api/getcallhistory',
                array (
                    'APIKEY' => APIKEY,
                    'card2query' => $this->data['usu_billing_cardusername'],
                )
            ),
            'momento' => factual_datetime_mysql()
        ));

        $response = getapi(fullpath($server->data['srv_name'].'/c53api/api/getcallhistory',
            array (
                'APIKEY' => APIKEY,
                'card2query' => $this->data['usu_billing_cardusername'],
            )
        ));

        $result = unserialize($response);

        return $result;
    }

    /*
     * Aplicamos saldo
     */
    function impute_saldo($amount, $pay_cod) {

        $adata = array (
            'APIKEY' => APIKEY,
            'card2query' => $this->data['usu_billing_cardusername'],
            'amount' => $amount,
            'payid' => $pay_cod
        );

        $server = new serversip($this->data['usu_srv_cod']);
        $server->load();

        $response = postapi(fullpath($server->data['srv_name'].'/c53api/api/setbalance'),
            json_encode($adata)
        );

        return $response;

    }

    /*
     * Genera el pin de acceso del usuario
     */
    function setPIN() {

        if (strlen($this->data['usu_key']) == 0) {
            $pin = rand(1000, 9990);
            $this->data['usu_key'] = $pin;
        } else {
            $pin = $this->data['usu_key'];
        }


        $sms_path = fullpath('api.smsarena.es/http/sms.php', array (
            'auth_key' => 'vIRoC5gGSXQ4YrKiJZzE1i49FOccRbOc',
            'from' => 'Phonealo',
            'to' => $this->data['usu_country_prefix'].$this->data['usu_mobile'],
            'text' => 'La clave para activar Phonealo es:  '.$pin,
            'id' => time()
        ), 'https');


        $envio = file_get_contents($sms_path);

        if (strpos($envio, 'ERROR') > 0) {
            $sms_path = fullpath('services.premiumnumbers.es:8080/push/sendPush', array (
                'idCliente' => 81,
                'clave' => 'b1gi6g14t8584ro',
                'remitente' => 'Phonealo',
                'destinatarios' => $this->data['usu_country_prefix'].$this->data['usu_mobile'],
                'texto' => 'La clave para activar Phonealo es:  '.$pin,
                'ruta' => 5,
                'alfabeto' => 0
            ));

            getapi($sms_path);
        }


    }

    function setServer($srv_cod = null) {
        $this->data['usu_srv_cod'] = (is_null($srv_cod))?2:$srv_cod;
    }

    function getExtension() {
        $ext_cod = $this->db->queryOneField('ext_cod', "SELECT * FROM extensions WHERE ext_usu_cod=%s", $this->cod);
        $ext = new extension($ext_cod);
        $ext->load();
        return $ext;
    }

    /*
     * Genera la extensión SIP
     */
    function genExtension() {
        /**
         * LO INCLUIMOS EN EL BILLING
         */
        global $dbBilling;
        $rowcallid = $dbBilling->queryFirstRow("select * from cc_callerid where id_cc_card = %s", $this->data['usu_billing_cardid']);
        if (count($rowcallid) == 0) {
            $dbBilling->insert(
                'cc_callerid', array (
                    'cid' => $this->data['usu_billing_cardusername'],
                    'id_cc_card' => $this->data['usu_billing_cardid'],
                    'activated' => 't'
                )
            );
            /**
             * LO CREAMOS EN EL FICHERO SIP.CONF
             */
            $template = file_get_contents(file_path.'sip_generated/appsip.tpl');
            $this->data['usu_sippass'] = $this->getSipPass();
            $sipBody = parseTemplate($template, $this->data, '{}');
            file_put_contents(sipconf_file, $sipBody, FILE_APPEND);
            exec('asterisk -rx "sip reload"');
        }
    }

    function getSipPass() {
        $tmpPass = strrev($this->data['usu_billing_cardusername']);
        return substr($tmpPass, 1, 5);
    }

    function loadById($id) {

        $usu_cod = $this->db->queryOneField('usu_cod',
                "SELECT * FROM usuario WHERE usu_billing_cardusername like %s", $id);

        if ($usu_cod) {
            $this->cod = $usu_cod;
            $this->load();
        }

    }

    function loadByMobilePrefix($mobile, $prefix) {
        $usu_cod = $this->db->queryOneField('usu_cod',
            "SELECT * FROM usuario WHERE concat(usu_country_prefix, usu_mobile) =%s", $prefix.$mobile);

        if (strlen($usu_cod) > 0) {
            $this->cod = $usu_cod;
            $this->load();
        }

    }

    function loadByCardId($cardid) {
        $usu_cod = $this->db->queryOneField('usu_cod',
            "SELECT * FROM usuario WHERE usu_billing_cardid =%s", $cardid);

        if (strlen($usu_cod) > 0) {
            $this->cod = $usu_cod;
            $this->load();
        }

    }

    function loadByMPPIN($mobile, $prefix, $pin) {
        $usu_cod = $this->db->queryOneField('usu_cod',
            "SELECT * FROM usuario WHERE concat(usu_country_prefix, usu_mobile) =%s and usu_key = %s", $prefix.$mobile, $pin);

        if (strlen($usu_cod) > 0) {
            $this->cod = $usu_cod;
            $this->load();
        } else {
            $this->cod = 0;
        }

    }

    function tablePayments() {

        $paydat = $this->db->query(
            'select * from payments a, usuario b where a.pay_usu_cod = b.usu_cod and pay_ok = 1 and usu_cod = %s',
            $this->cod
        );
        $table = new \html_table('payments', $paydat, 'pay_cod', 'class="table table-striped table-hover"');
        $table->add_column('fecha', 'Fecha', '{pay_timestamp}', '', 'timestamp2fecha');
        $table->add_column('importe', 'Recarga', '{pay_amount}');
        $table->add_column('idtpv', 'IDTPV', '{pay_pedidotpv}');

        $table->add_cell_foot('Totales:');

        return $table->print_table();
    }

    function tableCalls() {

        $calls = $this->getcallhistory();

        $table = new \html_table('calls', $calls['calls'], '', 'class="table table-striped table-hover"');
        $table->add_column('fecha', 'Fecha', '{starttime}', '', 'cambiaf_a_normal_time');
        $table->add_column('telefono', 'Destino', '{calledstation}');
        $table->add_column('duracion', 'Duración', '{sessiontime}');
        $table->add_column('coste', 'Importe', '{sessionbill}');

        return $table->print_table();

    }

}


