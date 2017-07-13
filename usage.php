<?php
	//описание и документация по API INTRUM http://www.intrumnet.com/api/
	
    if(!function_exists('pr')){
        //Отладочная функция
        function pr() {
            $args = func_get_args();
            foreach ($args as $item) {
                echo "<pre>";
                print_r($item);
                echo "</pre>";
            }
        }
    }

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
			"host"   => "intrum.local",//"yourdomain.intrumnet.com",
			"apikey" => "d78782cd25befcf9d0482a0847eb3be8",
			"cache"  => false,
			"port"   => 80
		)
	);

	// see /examples
	// require_once __DIR__ . '/example/file.name.php';
?>