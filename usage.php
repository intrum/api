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
			"host"   => "yourdomain.intrumnet.com",
			"apikey" => "b174cd05398db7cb2232b2a4119876f1",
			"cache"  => true
			//, "port" => 80 
		)
	);

	// see /examples
	// require_once __DIR__ . '/example/file.name.php';
?>