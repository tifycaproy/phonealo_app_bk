<?php
$strform = '';
if (!is_null(ppost('tabla'))) {

    $tabla = ppost('tabla');
    $prefijo = ppost('prefijo');
    $phpvar = ppost('phpvar');
    $formh = ppost('formh');
    $ncols = ppost('ncols');

    $tinfo = db_batch_fetch(db_query('describe '.$tabla));

    $tdata = $_POST;
    $table = db_get_field("select gtable from _gformdata where gtable like '$tabla'", 'gtable');

    if ($table == '') {
        db_insert('_gformdata', array (
            'gtable' => $tabla,
            'gdata' => json_encode($tdata)
        ));
    } else {

        if (count($_POST['label']) == 0) {
            $json_gdata = db_get_field("select gdata from _gformdata where gtable like '$tabla'", 'gdata');
            $tdata = unserialize($json_gdata);
        } else {
            db_update('_gformdata', array (
                'gdata' => serialize($tdata)
            ), array(
                'gtable' => $tabla
            ));
        }


    }

    if (count($_POST['label']) > 0 ) {

        $colsw = 12 / $ncols;
        $colcount = 0;
        foreach ($tinfo as $c => $campo) {

            if ($campo['Key'] == 'PRI') {
                $strrow = gf_pk($campo['Field'], ppost('prefijo'), ppost('phpvar'));
            } else {
                $colcount++;
                if (($colcount%$ncols) == 0 && $formh == 'S') {
                    $strrow = '<div class="row">'.chr(13);
                    $strrowfield = '';
                } else {
                    $strrow = '';
                    $strrowfield = '';
                }

                $info_campo = array (
                    'campo' => $campo['Field'],
                    'label' => $_POST['label'][$c],
                    'placeholder' => $_POST['placeholder'][$c]
                );

                if ($campo['Type'] == 'text') {
                    //$strrowfield = $strrowfield.gf_text($campo['Field'], $prefijo, $phpvar, $colsw, $formh);
                    $strrowfield = $strrowfield.gf_text($info_campo, $prefijo, $phpvar, $colsw, $formh);
                } elseif (substr($campo['Field'], -4) == '_cod') {
                    //$strrowfield = $strrowfield.gf_select($campo['Field'], $prefijo, $phpvar, $colsw, $formh);
                    $strrowfield = $strrowfield.gf_select($info_campo, $prefijo, $phpvar, $colsw, $formh);
                } else {
                    //$strrowfield = $strrowfield.gf_input($campo['Field'], $prefijo, $phpvar, $colsw, $formh);
                    $strrowfield = $strrowfield.gf_input($info_campo, $prefijo, $phpvar, $colsw, $formh);
                }


                if (($colcount%$ncols) == 0 && $formh == 'S')
                    $strrow = $strrow.$strrowfield.'</div>'.chr(13);
                else
                    $strrow = $strrow.$strrowfield;

            }

            $strform = $strform.$strrow;

        }

    }



    //die ($strform);

} else {
    $tabla = '';
    $prefijo = '';
    $phpvar = '';
    $formh = '';
    $ncols = '';
}


function gf_pk($campo, $prefijo, $phpvar) {
    $str = <<<EOF
<input name="{prefijo}_{campo}_pk" value="<?php echo {phpvar}['{campo}'] ?>" type="hidden"> \n
EOF;

    $values = array (
        'campo' => $campo,
        'prefijo' => $prefijo,
        'phpvar' => '$'.$phpvar
    );

    return gf_parse($values, $str);

}

function gf_input($campo, $prefijo, $phpvar, $colsw, $formh = 'S') {

    if ($formh == 'S') {
        $str = <<<EOF
        <div class="col-md-{colsw}">
            <div class="form-group">
                <label class="col-md-3 control-label">{label}:</label>
                <div class="col-md-9">
                    <input type="text" placeholder="{placeholder}"
                           id="{prefijo}_{campo}" name="{prefijo}_{campo}"
                           class="form-control" value="<?php echo {phpvar}['{campo}'] ?>">
                </div>
            </div>
        </div>\n
EOF;
    } else {
        $str = <<<EOF
        <div class="col-md-{colsw}">
            <div class="form-group">
                <label>{label}:</label>
                <input type="text" placeholder="{placeholder}" class="form-control"
                            id="{prefijo}_{campo}" name="{prefijo}_{campo}" value="<?php echo {phpvar}['{campo}'] ?>">
            </div>
        </div>\n
EOF;

    }

    $values = array (
        'campo' => $campo['campo'],
        'label' => $campo['label'],
        'placeholder' => $campo['placeholder'],
        'prefijo' => $prefijo,
        'phpvar' => '$'.$phpvar,
        'colsw' => $colsw
    );

    return gf_parse($values, $str);

}

function gf_select($campo, $prefijo, $phpvar, $colsw, $formh = 'S') {

    if ($formh == 'S') {
        $str = <<<EOF
        <div class="col-md-{colsw}">
            <div class="form-group">
                <label class="col-md-3 control-label">{label}:</label>
                <div class="col-md-9">
                    <select class="form-control" id="{prefijo}_{campo}" name="{prefijo}_{campo}">
                        <option value="<?php echo {phpvar}['{campo}'] ?>"><?php echo {phpvar}['{campo}'] ?></option>
                    </select>
                </div>
            </div>
        </div>\n
EOF;
    } else {
        $str = <<<EOF
        <div class="col-md-{colsw}">
            <div class="form-group">
                <label>{label}:</label>
                <select class="form-control" id="{prefijo}_{campo}" name="{prefijo}_{campo}">
                    <option value="<?php echo {phpvar}['{campo}'] ?>"><?php echo {phpvar}['{campo}'] ?></option>
                </select>
            </div>
        </div>\n
EOF;

    }

    $values = array (
        'campo' => $campo['campo'],
        'label' => $campo['label'],
        'placeholder' => $campo['placeholder'],
        'prefijo' => $prefijo,
        'phpvar' => '$'.$phpvar,
        'colsw' => $colsw
    );

    return gf_parse($values, $str);

}

function gf_text($campo, $prefijo, $phpvar, $colsw, $formh = 'S') {

    if ($formh == 'S') {
        $str = <<<EOF
        <div class="col-md-{colsw}">
            <div class="form-group">
                <label class="col-md-3 control-label">{label}:</label>
                <div class="col-md-9">
                    <textarea rows="4" class="form-control" id="{prefijo}_{campo}" name="{prefijo}_{campo}" placeholder="{placeholder}"><?php echo {phpvar}['{campo}'] ?></textarea>
                </div>
            </div>
        </div>\n
EOF;
    } else {
        $str = <<<EOF
        <div class="col-md-{colsw}">
            <div class="form-group">
                <label>{label}</label>
            <textarea rows="4" class="form-control" id="{prefijo}_{campo}" name="{prefijo}_{campo}" ><?php echo {phpvar}['{campo}'] ?></textarea>
        </div>


        </div>\n
EOF;

    }

    $values = array (
        'campo' => $campo['campo'],
        'label' => $campo['label'],
        'placeholder' => $campo['placeholder'],
        'prefijo' => $prefijo,
        'phpvar' => '$'.$phpvar,
        'colsw' => $colsw
    );

    return gf_parse($values, $str);

}


function gf_parse($atag, $strfield) {

    $tags = array ();
    foreach ($atag as $key => $value) {
        $tags[] = '{'.$key.'}';
        $replace[] = $value;
    }
    return str_replace($tags, $replace, $strfield);
}

