<?php require_once './vendor/autoload.php';

$api = new \iHerb\API();

$collection = $api->getByCategory('Sports-Nutrition');

print_r($collection->toArray());