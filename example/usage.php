<?php
//описание и документация по API INTRUM http://www.intrumnet.com/api/
	
	require_once __DIR__ . '/api.intrum.php';
	require_once __DIR__ . '/cache.intrum.php';
	
	IntrumExternalCache::getInstance()->setup(
		array(
			"folder" => __DIR__ . "/cache",
			"expire" => 600
		)
	);
	
	$api = IntrumExternalAPI::getInstance()
	->setup(
		array(
			"host"   => 'intrum.local',
            "apikey" => 'd78782cd25befcf9d0482a0847eb3be8',
            "port"   => 80,
            "cache"  => false //Существует лимит га 200 щапросов в час, потому в production при одинаковых запросах лучше активировать кеширование
		)
	);

?>