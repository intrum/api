<?php

//Добавление клиента и заявки к нему

require_once '../usage.php'; 

$res = $api->addRequestAndCustomer(array(
	'name'    => "Тест",
	'surname' => 'Тестович'
),array(
	'request_type'   => 10,
	'request_name'   => "Проверка",
	'request_status' => 'uselected'
));

print_r($res);
