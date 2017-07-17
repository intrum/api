<?php
	//описание и документация по API INTRUM http://www.intrumnet.com/api/

	require_once __DIR__ . '/Intrum/Api.php';
	require_once __DIR__ . '/Intrum/cache.php';
	
	/*IntrumExternalCache::getInstance()->setup(
		array(
			"folder" => __DIR__ . "/cache",
			"expire" => 600
		)
	);*/
	
	$api = IntrumExternalAPI::getInstance()
	->setup(
		array(
			"host"   => "intrum.local",//"yourdomain.intrumnet.com",
			"apikey" => "d78782cd25befcf9d0482a0847eb3be8",
			"cache"  => false,
			"port"   => 80
		)
	);

?>