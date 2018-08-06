<?php
header('Content-Type: application/json');

$db->insert('log', array (
    'action' => 'Respuesta de registro : '.$data->{'mobile'},
    'info' => json_encode($response)
));

echo json_encode($response);