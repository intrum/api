<?php

/* 
 * 
 * Получение всех связанных объектов в виде дерева
 * 
 */

require_once '../usage.php'; //настройте данный конфигурационный файл
//Список всех групп объектов     
$groups = $api->getStockGroups();
//Берём первую группу
$myGroup = $groups['data'][0];
//Выбираем список все сгруппированнх объектов данной группы
$data = $api->getStockByFilter(array(
    'group_id' => $myGroup['id'],
	'type'     => $myGroup['type'],
	'limit'    => 500
));
//Строим дерево сгруппированных объектов
$res = $api->createTreeStockGroup($data['data']['list']);

pr($res);