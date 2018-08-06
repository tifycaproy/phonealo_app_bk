<?php

if (pget('failed') && substr(pget('failed'), -1) == '0') {
    redirect(path('payment/init', array ('faildata' => pget('failed'))));
} else {
    redirect(path('payment/init'));
}

die(0);