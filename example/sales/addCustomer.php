<?php

//Добавление клиента с сделкой

require_once '../usage.php'; 

$res = $api->addSaleAndCustomer(array(
	'name'    => "Тест",
	'surname' => 'Тестович'
),array(
	"employee_id"      => 1 ,             
	"additional_employee_id" => [2,3],  
	"sales_type_id"   => 2,           
	"sales_status_id" => 23,         
	"sale_name"       => "Тестовая сделка",              
	"fields" => array(
		array(
			"id"    => 99,
			"value" => "Тест"
		)
	)
));

print_r($res);

