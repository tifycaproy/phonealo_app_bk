<?php
$usu = new \CompayPhone\usuario();
$usu->loadByMobilePrefix(pget('mobile'), pget('country'));
$usu_data = $usu->data;
echo $usu_data['usu_name'].', justo aquí estamos trabajando, te avisamos en breve';

die(0);