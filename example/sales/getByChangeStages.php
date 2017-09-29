<?php

/*
 *  Выборка сделок, стадия которых менялась за период "2017-05-01" - "2017-07-19"
 */

require_once '../usage.php'; 


/*$res = $api->getSalesChangeStage(array(
    'date_start' => date('Y-m-d', strtotime("-1 week")),
    'date_end'   => date('Y-m-d', time())
));*/

//print_r($res);


$res = $api->filterSales(array(
    //'by_ids'  => $res['data']['list']
	/*'sale_activity_date' => array(
		'from' => date('Y-m-d', strtotime("-1 week")),
		'to' => date('Y-m-d', time())
    )*/
	'date_field' => 'sale_activity_date',
	'date' => array(
		'from' => date('Y-m-d', strtotime("-1 week")),
		'to'   => date('Y-m-d', time())
	),
	'publish' => 0,
	'page'    => 2,
	'limit'   => 5
));

print_r($res);

