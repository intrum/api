<?php

	# 
	#	Пример выборки счетов
    #	по которым производилась оплата в период с '2016-07-01' по '2017-07-19'
	#

	require_once '../usage.php'; //настройте данный конфигурационный файл
	
	$res = $api->billsGet(array(
        'period_pay' => array(
            'date_start' => '2016-07-01',
            'date_end'   => '2017-07-19'
        )
    )); 
	
	print_r($res);
	
?>