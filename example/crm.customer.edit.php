<?php
//описание и документация по API INTRUM http://www.intrumnet.com/api/


	require_once __DIR__ . '/usage.php'; //настройте данный конфигурационный файл
	
	
	/*
	 * Пример редактирования клиента
	 */

	return $api->updateCustomers(
		// массив обновляемых записей
		array(
			// отдельная запись
			array(
				'id' => 277068,
				'surname' => 'Иванов',
				'name' => 'Владимир',
				'fields' => array(
					array(
						'id' => 945,
						'value' => 1
					)
				)
			)
		)
	);
?>