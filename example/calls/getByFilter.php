<?php

	require_once '../usage.php'; //настройте данный конфигурационный файл
	
	/*
	 * Пример добавления звонка
	 */
    
	$res = $api->callsGetList(array(
        'dateFrom' => "2017-07-19",
        'dateTo'   => "2017-07-23"
    ));
    
    print_r($res);
    
?>