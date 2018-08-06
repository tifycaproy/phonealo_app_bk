<?php

$have_post = false;
if (count($_POST['mala']) > 0) {
    $amala = $_POST['mala'];
    $last_cod = 0;
    $ayear = $_POST['year'];
    foreach ($amala as $cod => $ok) {
        $db->update('ec_cedulas', array ('cel_ok' => $ok, 'cel_year' => $ayear[$cod]), 'cel_cod = %d', $cod);
        $last_cod = $cod;
    }
    $lote = $db->queryFirstField('select cel_lote from ec_cedulas where cel_cod = %s', $last_cod);
    $have_post = true;

}

if (pget('lote')) {
    $lote = pget('lote');
    $have_post = true;
    $lotefecha = $db->queryFirstField('select cel_lote_fecha from ec_cedulas where cel_lote = %s', $lote);
    echo '<h3>Lote: '.cambiaf_a_normal_time($lotefecha).'</h3>';
}

if (pget('cedula')) {

    $cedula = pget('cedula');
    $ch = curl_init('http://www.mdi.gob.ec/minterior1/antecedentes/data.php');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    //curl_setopt($ch, CURLOPT_POSTFIELDS, 'tipo=getDataWsRc&ci='.$cedula);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'tipo=getDataWs&tp=C&ise=SI&ci=0401496823'.$cedula);
    curl_setopt($ch, CURLOPT_COOKIE, 'emHash=sug9nfivfv4iargkec8jqaaht0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /*
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen(40),
            'Referer: http://www.mdi.gob.ec/minterior1/antecedentes/'
        )
    );
    */

    $result = curl_exec($ch);
    /*
    $info = curl_getinfo($ch);
    print_object($info);
    */
    curl_close($ch);
    print_object(json_decode($result));
    die(0);
}

if (pget('gen') == 'do' && $have_post == false) {

    $gencel = new ValidarIdentificacion();
    $cedula = str_pad(rand(1,23), '0', STR_PAD_LEFT).rand(1,5).rand(100000, 999999);
    $digitoVerificador = $gencel->genModulo10($cedula);

    $cel_cojera = '1756684427';
    $cel_base = '175101546';

    $lote = $db->queryFirstField('select max(cel_lote) from ec_cedulas') + 1;

    $dcel = $db->query('select * from ec_cedulas where cel_ok = 1 order by cel_cod desc limit 150');
    $a_celbase = array ();
    foreach ($dcel as $cel) {
        $a_celbase[$cel['cel_num']] = $cel['cel_year'];
    }

    if (pget('n')) {
        $max = pget('n');
    } else {
        $max = 10;
    }

    $limit = 20;
    for ($i; $i<=$max; $i++) {
        $cel_select = array_rand($a_celbase);
        $top = intval(substr($cel_select, 6, 3)) - 1;

        $down = $top - $limit;
        if ($down < 0) {
            $down = $top;
            $top = $down - $limit;
        }
        $cel_base =  substr($cel_select, 0, 6).rand($down, $top);
        $digitoVerificador = $gencel->genModulo10($cel_base);
        //echo $cel_base.$digitoVerificador.' - '.$a_celbase[$cel_select]." <a target='_blank' href='ec-cedula?cedula=".$cel_base.$digitoVerificador."'> Ver ...</a>".'<br>';
        $db->insert('ec_cedulas',
            array (
                'cel_num' => $cel_base.$digitoVerificador,
                'cel_year' => $a_celbase[$cel_select],
                'cel_ok' => 1,
                'cel_lote' => $lote,
                'cel_lote_fecha' => factual_datetime_mysql()
            )
        );
    }
}


/*
for ($i; $i<=$max; $i++) {
    $cel_select = array_rand($a_celbase);
    $top = intval(substr($cel_select, 6, 3)) - 1;
    $down = $top - 20;
    if ($down < 0) {
        $down = $top;
        $top = $down - 20;
    }
    $cel_base =  substr($cel_select, 0, 6).rand($down, $top);
    $digitoVerificador = $gencel->genModulo10($cel_base);
    echo $cel_base.$digitoVerificador.' - '.$a_celbase[$cel_select]." <a target='_blank' href='ec-cedula?cedula=".$cel_base.$digitoVerificador."'> Ver ...</a>".'<br>';
}
*/


//$res_cel = $db->query('select * from ec_cedulas where cel_movi <= 6 order by rand() limit %d', $max);
$res_cel = $db->query('select * from ec_cedulas where cel_lote = %d', $lote);

if (pget('n')) {
    $max = pget('n');
} else {
    $max = 10;
}



/**
 * ValidarIdentificacion contiene metodos para validar cédula, RUC de persona natural, RUC de sociedad privada y
 * RUC de socieda pública en el Ecuador.
 *
 * Los métodos públicos para realizar validaciones son:
 *
 * validarCedula()
 * validarRucPersonaNatural()
 * validarRucSociedadPrivada()
 */
class ValidarIdentificacion
{
    /**
     * Error
     *
     * Contiene errores globales de la clase
     *
     * @var string
     * @access protected
     */
    protected $error = '';
    /**
     * Validar cédula
     *
     * @param  string  $numero  Número de cédula
     *
     * @return Boolean
     */
    public function validarCedula($numero = '')
    {
        // fuerzo parametro de entrada a string
        $numero = (string)$numero;
        // borro por si acaso errores de llamadas anteriores.
        $this->setError('');
        // validaciones
        try {
            $this->validarInicial($numero, '10');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'cedula');
            $this->algoritmoModulo10(substr($numero, 0, 9), $numero[9]);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
        return true;
    }
    /**
     * Validar RUC persona natural
     *
     * @param  string  $numero  Número de RUC persona natural
     *
     * @return Boolean
     */
    public function validarRucPersonaNatural($numero = '')
    {
        // fuerzo parametro de entrada a string
        $numero = (string)$numero;
        // borro por si acaso errores de llamadas anteriores.
        $this->setError('');
        // validaciones
        try {
            $this->validarInicial($numero, '13');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'ruc_natural');
            $this->validarCodigoEstablecimiento(substr($numero, 10, 3));
            $this->algoritmoModulo10(substr($numero, 0, 9), $numero[9]);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
        return true;
    }
    /**
     * Validar RUC sociedad privada
     *
     * @param  string  $numero  Número de RUC sociedad privada
     *
     * @return Boolean
     */
    public function validarRucSociedadPrivada($numero = '')
    {
        // fuerzo parametro de entrada a string
        $numero = (string)$numero;
        // borro por si acaso errores de llamadas anteriores.
        $this->setError('');
        // validaciones
        try {
            $this->validarInicial($numero, '13');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'ruc_privada');
            $this->validarCodigoEstablecimiento(substr($numero, 10, 3));
            $this->algoritmoModulo11(substr($numero, 0, 9), $numero[9], 'ruc_privada');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
        return true;
    }
    /**
     * Validar RUC sociedad publica
     *
     * @param  string  $numero  Número de RUC sociedad publica
     *
     * @return Boolean
     */
    public function validarRucSociedadPublica($numero = '')
    {
        // fuerzo parametro de entrada a string
        $numero = (string)$numero;
        // borro por si acaso errores de llamadas anteriores.
        $this->setError('');
        // validaciones
        try {
            $this->validarInicial($numero, '13');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'ruc_publica');
            $this->validarCodigoEstablecimiento(substr($numero, 9, 4));
            $this->algoritmoModulo11(substr($numero, 0, 8), $numero[8], 'ruc_publica');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
        return true;
    }
    /**
     * Validaciones iniciales para CI y RUC
     *
     * @param  string  $numero      CI o RUC
     * @param  integer $caracteres  Cantidad de caracteres requeridos
     *
     * @return Boolean
     *
     * @throws exception Cuando valor esta vacio, cuando no es dígito y
     * cuando no tiene cantidad requerida de caracteres
     */
    protected function validarInicial($numero, $caracteres)
    {
        if (empty($numero)) {
            throw new Exception('Valor no puede estar vacio');
        }
        if (!ctype_digit($numero)) {
            throw new Exception('Valor ingresado solo puede tener dígitos');
        }
        if (strlen($numero) != $caracteres) {
            throw new Exception('Valor ingresado debe tener '.$caracteres.' caracteres');
        }
        return true;
    }
    /**
     * Validación de código de provincia (dos primeros dígitos de CI/RUC)
     *
     * @param  string  $numero  Dos primeros dígitos de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando el código de provincia no esta entre 00 y 24
     */
    protected function validarCodigoProvincia($numero)
    {
        if ($numero < 0 OR $numero > 24) {
            throw new Exception('Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');
        }
        return true;
    }
    /**
     * Validación de tercer dígito
     *
     * Permite validad el tercer dígito del documento. Dependiendo
     * del campo tipo (tipo de identificación) se realizan las validaciones.
     * Los posibles valores del campo tipo son: cedula, ruc_natural, ruc_privada
     *
     * Para Cédulas y RUC de personas naturales el terder dígito debe
     * estar entre 0 y 5 (0,1,2,3,4,5)
     *
     * Para RUC de sociedades privadas el terder dígito debe ser
     * igual a 9.
     *
     * Para RUC de sociedades públicas el terder dígito debe ser
     * igual a 6.
     *
     * @param  string $numero  tercer dígito de CI/RUC
     * @param  string $tipo  tipo de identificador
     *
     * @return boolean
     *
     * @throws exception Cuando el tercer digito no es válido. El mensaje
     * de error depende del tipo de Idenficiación.
     */
    protected function validarTercerDigito($numero, $tipo)
    {
        switch ($tipo) {
            case 'cedula':
            case 'ruc_natural':
                if ($numero < 0 OR $numero > 5) {
                    throw new Exception('Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');
                }
                break;
            case 'ruc_privada':
                if ($numero != 9) {
                    throw new Exception('Tercer dígito debe ser igual a 9 para sociedades privadas');
                }
                break;
            case 'ruc_publica':
                if ($numero != 6) {
                    throw new Exception('Tercer dígito debe ser igual a 6 para sociedades públicas');
                }
                break;
            default:
                throw new Exception('Tipo de Identificación no existe.');
                break;
        }
        return true;
    }
    /**
     * Validación de código de establecimiento
     *
     * @param  string $numero  tercer dígito de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando el establecimiento es menor a 1
     */
    protected function validarCodigoEstablecimiento($numero)
    {
        if ($numero < 1) {
            throw new Exception('Código de establecimiento no puede ser 0');
        }
        return true;
    }
    /**
     * Algoritmo Modulo10 para validar si CI y RUC de persona natural son válidos.
     *
     * Los coeficientes usados para verificar el décimo dígito de la cédula,
     * mediante el algoritmo “Módulo 10” son:  2. 1. 2. 1. 2. 1. 2. 1. 2
     *
     * Paso 1: Multiplicar cada dígito de los digitosIniciales por su respectivo
     * coeficiente.
     *
     *  Ejemplo
     *  digitosIniciales posicion 1  x 2
     *  digitosIniciales posicion 2  x 1
     *  digitosIniciales posicion 3  x 2
     *  digitosIniciales posicion 4  x 1
     *  digitosIniciales posicion 5  x 2
     *  digitosIniciales posicion 6  x 1
     *  digitosIniciales posicion 7  x 2
     *  digitosIniciales posicion 8  x 1
     *  digitosIniciales posicion 9  x 2
     *
     * Paso 2: Sí alguno de los resultados de cada multiplicación es mayor a o igual a 10,
     * se suma entre ambos dígitos de dicho resultado. Ex. 12->1+2->3
     *
     * Paso 3: Se suman los resultados y se obtiene total
     *
     * Paso 4: Divido total para 10, se guarda residuo. Se resta 10 menos el residuo.
     * El valor obtenido debe concordar con el digitoVerificador
     *
     * Nota: Cuando el residuo es cero(0) el dígito verificador debe ser 0.
     *
     * @param  string $digitosIniciales   Nueve primeros dígitos de CI/RUC
     * @param  string $digitoVerificador  Décimo dígito de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando los digitosIniciales no concuerdan contra
     * el código verificador.
     */
    protected function algoritmoModulo10($digitosIniciales, $digitoVerificador)
    {
        $arrayCoeficientes = array(2,1,2,1,2,1,2,1,2);
        $digitoVerificador = (int)$digitoVerificador;
        $digitosIniciales = str_split($digitosIniciales);
        $total = 0;
        foreach ($digitosIniciales as $key => $value) {
            $valorPosicion = ( (int)$value * $arrayCoeficientes[$key] );
            if ($valorPosicion >= 10) {
                $valorPosicion = str_split($valorPosicion);
                $valorPosicion = array_sum($valorPosicion);
                $valorPosicion = (int)$valorPosicion;
            }
            $total = $total + $valorPosicion;
        }
        $residuo =  $total % 10;
        if ($residuo == 0) {
            $resultado = 0;
        } else {
            $resultado = 10 - $residuo;
        }
        if ($resultado != $digitoVerificador) {
            throw new Exception('Dígitos iniciales no validan contra Dígito Idenficador');
        }
        return true;
    }

    public function genModulo10($digitosIniciales)
    {
        $arrayCoeficientes = array(2,1,2,1,2,1,2,1,2);
        $digitosIniciales = str_split($digitosIniciales);
        $total = 0;
        foreach ($digitosIniciales as $key => $value) {
            $valorPosicion = ( (int)$value * $arrayCoeficientes[$key] );
            if ($valorPosicion >= 10) {
                $valorPosicion = str_split($valorPosicion);
                $valorPosicion = array_sum($valorPosicion);
                $valorPosicion = (int)$valorPosicion;
            }
            $total = $total + $valorPosicion;
        }
        $residuo =  $total % 10;
        if ($residuo == 0) {
            $resultado = 0;
        } else {
            $resultado = 10 - $residuo;
        }
        return $resultado;
    }
    /**
     * Algoritmo Modulo11 para validar RUC de sociedades privadas y públicas
     *
     * El código verificador es el decimo digito para RUC de empresas privadas
     * y el noveno dígito para RUC de empresas públicas
     *
     * Paso 1: Multiplicar cada dígito de los digitosIniciales por su respectivo
     * coeficiente.
     *
     * Para RUC privadas el coeficiente esta definido y se multiplica con las siguientes
     * posiciones del RUC:
     *
     *  Ejemplo
     *  digitosIniciales posicion 1  x 4
     *  digitosIniciales posicion 2  x 3
     *  digitosIniciales posicion 3  x 2
     *  digitosIniciales posicion 4  x 7
     *  digitosIniciales posicion 5  x 6
     *  digitosIniciales posicion 6  x 5
     *  digitosIniciales posicion 7  x 4
     *  digitosIniciales posicion 8  x 3
     *  digitosIniciales posicion 9  x 2
     *
     * Para RUC privadas el coeficiente esta definido y se multiplica con las siguientes
     * posiciones del RUC:
     *
     *  digitosIniciales posicion 1  x 3
     *  digitosIniciales posicion 2  x 2
     *  digitosIniciales posicion 3  x 7
     *  digitosIniciales posicion 4  x 6
     *  digitosIniciales posicion 5  x 5
     *  digitosIniciales posicion 6  x 4
     *  digitosIniciales posicion 7  x 3
     *  digitosIniciales posicion 8  x 2
     *
     * Paso 2: Se suman los resultados y se obtiene total
     *
     * Paso 3: Divido total para 11, se guarda residuo. Se resta 11 menos el residuo.
     * El valor obtenido debe concordar con el digitoVerificador
     *
     * Nota: Cuando el residuo es cero(0) el dígito verificador debe ser 0.
     *
     * @param  string $digitosIniciales   Nueve primeros dígitos de RUC
     * @param  string $digitoVerificador  Décimo dígito de RUC
     * @param  string $tipo Tipo de identificador
     *
     * @return boolean
     *
     * @throws exception Cuando los digitosIniciales no concuerdan contra
     * el código verificador.
     */
    protected function algoritmoModulo11($digitosIniciales, $digitoVerificador, $tipo)
    {
        switch ($tipo) {
            case 'ruc_privada':
                $arrayCoeficientes = array(4, 3, 2, 7, 6, 5, 4, 3, 2);
                break;
            case 'ruc_publica':
                $arrayCoeficientes = array(3, 2, 7, 6, 5, 4, 3, 2);
                break;
            default:
                throw new Exception('Tipo de Identificación no existe.');
                break;
        }
        $digitoVerificador = (int)$digitoVerificador;
        $digitosIniciales = str_split($digitosIniciales);
        $total = 0;
        foreach ($digitosIniciales as $key => $value) {
            $valorPosicion = ( (int)$value * $arrayCoeficientes[$key] );
            $total = $total + $valorPosicion;
        }
        $residuo =  $total % 11;
        if ($residuo == 0) {
            $resultado = 0;
        } else {
            $resultado = 11 - $residuo;
        }
        if ($resultado != $digitoVerificador) {
            throw new Exception('Dígitos iniciales no validan contra Dígito Idenficador');
        }
        return true;
    }
    /**
     * Get error
     *
     * @return string Mensaje de error
     */
    public function getError()
    {
        return $this->error;
    }
    /**
     * Set error
     *
     * @param  string $newError
     * @return object $this
     */
    public function setError($newError)
    {
        $this->error = $newError;
        return $this;
    }
}
