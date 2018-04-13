<?php
    /* Вариант использования без composer*/
	require_once  '../../Intrum/Api.php';
	require_once  '../../Intrum/Cache.php';
	
	/*Intrum\Cache::getInstance()->setup(
		array(
			"folder" => __DIR__ . "/cache",
			"expire" => 600
		)
	);*/
	
	$api = Intrum\Api::getInstance()
	->setup(
		array(
			"host"   => "intrum.local",//"yourdomain.intrumnet.com",
			"apikey" => "d78782cd25befcf9d0482a0847eb3be8",
			"cache"  => false,
			"port"   => 80
		)
	);
?>