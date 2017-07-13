<?php

require_once __DIR__ . '/../../usage.php'; //настройте данный конфигурационный файл

$typesData = $api->getStockTypes();
/*  Результат в таком формате
array ( 
  'status' => 'success', 
  'data' => array ( 
       array ( 
          'id' => '20', 
          'name' => 'Главпродукт', 
          'groups' => array ( ) 
        )
  )
 */

//Массив id всех типов
$types = array_map(function($item){
    return $item['id'];
}, $typesData['data']);

$types = array( $types[0] );

//Выборка всех существующих полей
$fields = $api->getStockFields();

foreach($types as $type){

    $list = array();

    $data = $api->getStockByFilter(array(
        'type'  => $type,
        'page'  => 1,
        'limit' => 10, 
        'date'  => array(
            'from' => '2016-10-29 09:45:23', //С какой даты создания объекта
            'to'   => '2017-11-19 13:05:12'  //По какую дату
        )
    ));
    $list = $data['data']['list'];

    if($data['data']['count'] > 500 && 1==2){
        $pages = ceil($data['data']['count'] / 500);

        for($i=2; $i<=$pages; $i++){
            $data = $api->getStockByFilter(array(
                'type'  => $type,
                'page'  => $i,
                'limit' => 500 //Максимум 500
            ));
            $list = array_merge($list,$data['data']['list']);
        }
    }

    foreach($list as $key=>$item){
        foreach($item['fields'] as $fieldKey=>$field){
            if($field['type'] == 'file'){
                $list[$key]['fields'][$fieldKey]['value'] = $api->getStockUrlPhoto($field['value']);
            }
            $list[$key]['fields'][$fieldKey]['name'] = $fields['data'][$type]['fields'][ $field['id'] ]['name'];
        }
    }

    //$list - Все продукты данного типа, с фотографиями в виде ссылок, формат :
    /*
    array(
        array(
            [id] => 32916
            [stock_type] => 20
            [parent] => 8208
            [name] => Test iPhone11
            [date_add] => 2017-04-18 16:51:09
            [author] => 79
            [additional_author] => Array
                (
                )

            [last_modify] => 2017-07-13 11:02:07
            [customer_relation] => 
            [stock_activity_type] => edit
            [stock_activity_date] => 2017-07-13 11:02:07
            [publish] => 1
            [fields] => Array
                (
                    [0] => Array
                        (
                            [id] => 395
                            [type] => text
                            [value] => 1111
                            [name] => Артикул
                        )

                    [1] => Array
                        (
                            [id] => 396
                            [type] => price
                            [value] => 1000000.00
                            [name] => Цена
                        )

                    [5] => Array
                        (
                            [id] => 398
                            [type] => file
                            [value] => http://intrum.local/files/crm/product/595dd820cfe6a.jpg
                            [name] => Фото
                        )

                    [6] => Array
                        (
                            [id] => 398
                            [type] => file
                            [value] => http://intrum.local/files/crm/product/595dd828b030f.jpg
                            [name] => Фото
                        )

                )

        )
    )
    */
}
        
        //Максимум 500