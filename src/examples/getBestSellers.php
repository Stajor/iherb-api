<?php require_once './vendor/autoload.php';

$api = new \iHerb\API();

$collection = $api->getTopSellers();

print_r($collection->toArray());