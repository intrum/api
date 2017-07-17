<?php

/* 
 * 
 * Получение списка групп продуктов
 * 
 */

require_once __DIR__ . '/../../usage.php'; //настройте данный конфигурационный файл
    
$groups = $api->getStockGroups();

$myGroup = $goups['data'][0];

$data = $api->getStockByFilter(array(
    '' => $stockId
));