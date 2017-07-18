<?php

/*
 *  Пример получения всех клиентов
 */

require_once '../usage.php'; 

$res = $api->getListCustomers(array(
    'order_field' => 'id',
    'order' => 'DESC',
    'limit' => 10000 //Максимум
));

print_r($res);

