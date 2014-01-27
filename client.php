<?php

require_once 'TamboDb.php';

$client = new TamboDb("161.132.6.59", 6666);
if ($client->create("país", "Perú") == 1) {
    echo "llave creada.";
}
