<?php
include_once(file_path.'lib/secureAPI.php');

$usu = new \CompayPhone\usuario(90);
$usu->load();

print_object($usu);

$usu->setPIN();

die(0);