<?php

// выборка объектов базы данных (продуктов) с фильтром по определенному полю
// например, все объекты с ценой больше 30 000

require_once '../usage.php'; //настройте данный конфигурационный файл



$data = $api->getStockByFilter(array(
	"type"   =>  1,
	"fields" => array(
		array(
			"id" => 1863,
			"value" => array(
				"object" => "stock",
				"id"     =>  21921
			)
		)
	)
));

pr($data);


