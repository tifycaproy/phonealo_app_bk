<?php

$device = $_SERVER['HTTP_USER_AGENT'];

if (strpos($device, 'Android') > 0) {
    redirect('https://play.google.com/store/apps/details?id=com.call53&hl=es');
} elseif (strpos($device, 'Apple') > 0) {
    redirect('https://appsto.re/es/e4ODib.i');
}


