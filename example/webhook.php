<?php
//описание и документация по API INTRUM http://www.intrumnet.com/api/
//Пример обработки webhook событий
$input = file_get_contents('php://input');
$data = json_decode($input,1);

//Структура дата зависит от объекта события и типа события и расписана в документации http://www.intrumnet.com/api/#logwebhook
