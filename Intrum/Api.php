<?php
	
    namespace Intrum;

	class Api
	{
		private static $instance = null;
	
		public function __construct(){}
		
		public static function getInstance()
		{
			if(null === self::$instance){
				self::$instance = new self();
			}

			return self::$instance;
		}
		
		public function setup(array $params)
		{
			$this->key   = $params['apikey'];
			$this->host  = $params['host'];
			$this->port  = (isset($params['port']) ? ((int)$params['port']): 81);
			$this->url   = "http://{$this->host}:{$this->port}/sharedapi";
			$this->cache = $params['cache'];
            
			
			return $this;
		}
		
        private $debug = 0;
        public $debugStack = array();
        public function setDebug($set)
        {
            $this->debug = $set;
        }
        
        public function printDebug()
        {
            return $this->debugStack;
        }
		/*
			Продукты
		*/
		
		// типы продуктов
		public function getStockTypes()
		{
			return $this->send("/stock/types");
		}
		
		// список категорий
		public function getStockCategory()
		{
			return $this->send("/stock/category");
		}
		
		// поля
		public function getStockFields()
		{
			return $this->send("/stock/fields");
		}
		
		// поиск
		public function getStockByFilter(array $params)
		{
			return $this->send("/stock/filter",$params);
		}
        
        //Получение конкретного объекта
        public function getStockById($id)
        {
            $data = $this->getStockByFilter(array(
                'byid' => $id
            ));
            return $data['data']['list'][0];
        }
        
        //Картинки объектов
        public function getStockUrlPhoto($name)
        {
            return "http://" .$this->host . "/files/crm/product/" . rawurlencode($name);
        }
		
		//вставка
		public function insertStock(array $params)
		{
			return $this->send("/stock/insert",$params);
		}
		
		//обновление
		public function updateStock(array $params)
		{
			return $this->send("/stock/update",$params);
		}
		
		//удаление
		public function deleteStock(array $params)
		{
			return $this->send("/stock/delete",$params);
		}
		
		// комментарии
		public function getStockComments($entity_id)
		{
			return $this->send("/stock/comments",array(
				'entity_id' => $entity_id
			));
		}
        
        //Добавление комментариев
        /*
         *  $params : {
         *      enity_id, обзательный
         *      text,     обязательный
         *      author    опционально
         *  }
         */
        public function addStockComment(array $params)
        {
            return $this->send("/stock/addComment",$params);
        }
        
        //список групп объектов
        public function getStockGroups()
        {
            return $this->send('/stock/groups');
        }
		
        //Создание дерева групп объектов из списка объектов
        public function createTreeStockGroup($list)
        {
            $tree = array();
            foreach($list as $key=>$item){
                if($item['copy'] == 0){
                    $item['childs'] = array();
                    $tree[ $item['id'] ] = $item;
                    unset($list[$key]);
                }
            }
            
            foreach($list as $item){
                $tree[ $item['copy'] ]['childs'][] = $item;
            }
            return $tree;
        }
        
		/*
			Сотрудники
		*/
		
		// поля
		public function getEmployeeFields()
		{
			return $this->send("/worker/fields");
		}
		
		// отделы
		public function getDepartment()
		{
			return $this->send("/worker/department");
		}
		
		// филиалы
		public function getFiliation()
		{
			return $this->send("/worker/filiation");
		}
		
		// поиск
		public function filterEmployee(array $params = array())
		{
			return $this->send("/worker/filter",$params);
		}
		
		/*
			Группы менеджеров
		*/
		
		//получение списка групп
		public function getAvailGroups()
		{
			return $this->send("/managergroup");
		}
		
		/*
			Статьи
		*/
		
		// список статей
		public function getArticlesList(array $params)
		{
			return $this->send("/publication/list",$params);
		}
		
		// содержимое статьи
		public function getArticleContent(array $params)
		{
			return $this->send("/publication/single",$params);
		}
		
		/*
			Заявки
		*/
		
		// типы заявок
		public function getRequestTypes()
		{
			return $this->send("/applications/types");
		}
		
		// поля
		public function getRequestFields()
		{
			return $this->send("/applications/fields");
		}
		
		// поиск
		public function filterRequests(array $params)
		{
			return $this->send("/applications/filter",$params);
		}
		
		//вставка
		public function insertRequests(array $params)
		{
			return $this->send("/applications/insert",$params);
		}
		
		//обновление
		public function updateRequests(array $params)
		{
			return $this->send("/applications/update",$params);
		}
		
        //Добавление комментариев
        /*
         *  $params : {
         *      enity_id, обзательный
         *      text,     обязательный
         *      author    опционально
         *  }
         */
        public function addRequestComment(array $params) 
        {
            return $this->send("/applications/addComment", $params);
        }

        //удаление
		public function deleteRequests(array $params)
		{
			return $this->send("/applications/delete",$params);
		}
		
		// комментарии
		public function getRequestComments($entity_id)
		{
			return $this->send("/applications/comments",array(
				'entity_id' => $entity_id
			));
		}
		
		/*
			Продажи
		*/
        
		/*  Список типов продаж
         */
		public function getSaleTypes()
		{
			return $this->send("/sales/types");
		}
		
        /*  Список fields для продаж
         */
		public function getSaleFields()
		{
			return $this->send("/sales/fields");
		}
		
        //Выборка продаж по фильтру
		public function filterSales(array $params)
		{
			return $this->send("/sales/filter",$params);
		}
        
		//Добавление сделки
        /*
         *  $params [
         *      {
                   customers_id            - id клиента
                   employee_id             - id ответственного менеджера
                   additional_employee_id  - массив id дополнительных менеджеров
                   sales_type_id           - id типа продажи
                   sales_status_id         - id стадии продажи
                   sale_name               - название продажи
                   fields                  - массив данных полей
         *      }, 
         *      ...
         *  ]
         */
		public function insertSales(array $params)
		{
            if(!$this->checkParamArr($params)){
                return array(
                    'error' => "Неправильный формат данных"
                );
            }
			return $this->send("/sales/insert",$params);
		}
		
        private function checkParamArr($params)
        {
            print_r(gettype($params[0]));
            if(gettype($params[0]) == 'array'){
                return true;
            }
        }
        
		//Редактирование сделки
		public function updateSales(array $params)
		{
			return $this->send("/sales/update",$params);
		}
		
		//удаление
		public function deleteSales(array $params)
		{
			return $this->send("/sales/delete",$params);
		}
		
		//доп инфа по сделке
		public function getSaleDetails(array $params)
		{
			return $this->send("/sales/details",$params);
		}
		
		// комментарии
		public function getSalesComments($entity_id)
		{
			return $this->send("/sales/comments",array(
				'entity_id' => $entity_id
			));
		}
        
        //Добавление комментариев
        /*
         *  $params : {
         *      enity_id, обзательный
         *      text,     обязательный
         *      author    опционально
         *  }
         */
        public function addSalesComment(array $params) {
            return $this->send("/sales/addComment", $params);
        }

        /*
			Клиенты
		*/
		
		// поля
		public function getCustomerFields()
		{
			return $this->send("/purchaser/fields");
		}
		
		// поиск
        /*
            $params {
                groups - массив id групп менеджеров
                manager - id менеджера
                byid - id клиента
                marktype - массив id типов
                nattype - одно из значений подтипа physface - Юрлицо, jurface - Физлицо, по умолчанию выводятся все
                search - поисковая строка (может содержать фамилию или имя, email, телефон)
                fields - массив условий для дополнительных свойств [ {id,value}, ]
                order - направление сортировки asc - по возрастанию, desc - по убыванию
                order_field - если в качестве значения указать customer_activity_date выборка будет сортироваться по дате активности
                date - {from: "2015-10-29", to: "2015-11-19"} выборка за определенный период
                date_field - если в качестве значения указать customer_activity_date выборка по параметру активности
                page - страница
                publish - 1 - активные, 0 - удаленные, по умолчанию 1
                limit - число записей в выборке (макс. 50)
            }
         */
		public function filterCustomers(array $params)
		{
			return $this->send("/purchaser/filter",$params);
		}
		
        /*
         *  Обёртка над filterCustomers
         *  $params те-же что и у filterCustomers, но limit = 10000
         *  файлы возвращаются в формате {name: имя файла, link:путь к файлу}
         */
        public function getListCustomers(array $params)
        {
            $total = ($params['limit']) ? $params['limit'] : 10000;
            if($total > 50){
                $params['limit'] = 50;
            }
            $count = $params['count'];
            $max = 199;
            $page = 0;
            $list = array();
            while($page <= $max){
                $page ++;
                $params['page'] = $page;
                if($total < $params['limit']){
                    $params['limit'] = $total;
                }
                $data = $this->filterCustomers($params);
                if(!$data['data']['list']){
                    break;
                }
                $list = array_merge($list,$data['data']['list']);
                $total -= count($data['data']['list']);
            }
            
            foreach($list as $key=>$item){
                foreach($item['fields'] as $key2=>$field){
                    if($field['datatype'] == 'file'){
                        $list[$key]['fields'][$key2]['link'] = $this->getCustomerUrlFile($field['value']);
                    }
                }
            }
            
            return array(
                'list'  => $list,
                'count' => $data['data']['count']
            );
        }
        
        //Файлы клиентов
        public function getCustomerUrlFile($name) {
            return "http://" . $this->host . "/files/crm/" . rawurlencode($name);
        }

        //Добавление клиента
        /*
         *  $params {
               name                  - Имя
               surname               - Фамилия
               secondname            - Отчество
               manager_id            - ID менеджера
               additional_manager_id - Массив ID дополнительных менеджеров
               marktype              - Тип
               email                 - массив email адресов
               phone                 - массив номеров телефонов
               fields                - Массив допполей
         *  }
         */
		public function insertCustomers(array $params)
		{
			return $this->send("/purchaser/insert",$params);
		}
		
		//обновление
		public function updateCustomers(array $params)
		{
			return $this->send("/purchaser/update",$params);
		}
		
        //Добавление комментариев
        /*
         *  $params : {
         *      enity_id, обзательный
         *      text,     обязательный
         *      author    опционально
         *  }
         */
        public function addCustomersComment(array $params) {
            return $this->send("/purchaser/addComment", $params);
        }

        //удаление
		public function deleteCustomers(array $params)
		{
			return $this->send("/purchaser/delete",$params);
		}
		
		// комментарии
		public function getCustomerComments($entity_id)
		{
			return $this->send("/purchaser/comments",array(
				'entity_id' => $entity_id
			));
		}
		
		// прикрепления
		public function getCustomerAttaches($entity_id)
		{
			return $this->send("/purchaser/attach",array(
				'ids' => $entity_id 
			));
		}
		
		/*
			Счета
		*/
		
		//Поиск / выборка
		public function billsGet(array $params
		){
			return $this->send("/accounts/get",$params);
		}
		
		//Получение подробной информации по массиву счетов
		public function billsGetFull(array $ids)
		{
			return $this->send("/accounts/get_full",array('ids' => $ids));
		}
		
		//добавление
		public function billsAdd(array $params)
		{
			return $this->send("/accounts/add",$params);
		}
		
		//обновление
		public function billsUpdate(array $params)
		{
			return $this->send("/accounts/update",$params);
		}
		
		//Редактирование
		public function billsEdit(array $params)
		{
			return $this->send("/accounts/edit",$params);
		}
		
		//Установить статус оплаты
		public function billsSetPay($id,$pay=null)
		{
			return $this->send("/accounts/set_pay",array('id'=>$id,'pay'=>$pay));
		}
		
		/*
			Акты
		*/
		//Поиск / выборка
		public function actsGet(array $params)
		{
			return $this->send("/acts/get",$params);
		}
		
		//добавление
		public function actsAdd(array $params)
		{
			return $this->send("/acts/add",$params);
		}
		
		//обновление
		public function actsUpdate(array $params)
		{
			return $this->send("/acts/update",$params);
		}
		
		//Редактирвоание
		public function actsEdit(array $params)
		{
			return $this->send("/acts/edit",$params);
		}
		//Установить статус оплаты
		public function actsSetPay($id,$pay=null)
		{
			return $this->send("/acts/set_pay",array('id'=>$id,'pay'=>$pay));
		}
		
		/*
			Выписки
		*/
		//Поиск / выборка
		public function checksGet(array $params)
		{
			return $this->send("/checks/get",$params);
		}
		
		//добавление
		public function checksAdd(array $params)
		{
			return $this->send("/checks/add",$params);
		}
		
		//обновление
		public function checksUpdate(array $params)
		{
			return $this->send("/checks/update",$params);
		}
		
		/* 
			Служебные 
		*/
		 
		// варианты выбора
		public function getSelectVariants(array $params)
		{
			return $this->send("/utils/variants",$params);
		}
		
		// дочерние варианты выбора привязанные к конкретному варианту родителя 
		public function getBindedSelectVariants(array $params)
		{
			return $this->send("/utils/binded",$params);
		}
		
        
        /*
         *  object - один из возможных вариантов (stock-Продукт, applications-Заявки, purchaser-Клиент)
         *  $fieldId - (int) id поля файла
         *  $list = {
         *      $idObject( ID сущности CRM ) : [
         *          "upload.jpg",
         *          "upload1.jpg" //Список абсолютных путей к картинкам
         *      ]
         *  }
         */
        public function addFilesToObjects($objectType,$fieldId,$list)
        {
            $map = array();
            $all = array();
            foreach($list as $object=>$images){
                foreach($images as $img){
                    $map[] = $object;
                    $all[] = $img;
                }
            }
            $upp = array();
            
            $res = $this->uploadFile($objectType, $all);
            
            if($res['status'] === 'success'){
                foreach($res['data']['name'] as $num=>$item){
                    $upp[ $map[$num] ]['id'] = $map[$num];
                    $upp[ $map[$num] ]['fields'][] = array(
                        'id'    => $fieldId,
                        'value' => $item,
                        'mode'  => 'insert' 
                    );
                }
                
                if($objectType == 'stock'){
                    $res = $this->updateStock($upp);
                }elseif($objectType == 'applications'){
                    $res = $this->updateSales($upp);
                }elseif($objectType == 'purchaser'){
                    $res = $this->updateCustomers($upp);
                }
                
               return $res;
            }
            
        }
        
		// загрузчик файлов
        /*
         *  object - один из возможных вариантов (stock-Продукт, applications-Заявки, purchaser-Клиент)
            upload - имя поля загружаемого файла, поддерживает множественную загрузку
         * 
         */
		public function uploadFile($object,$source)
		{
			$is_multiple = is_array($source);
			$source = (array) $source;
            
			foreach($source as $i => $s){
				if(!file_exists($s) or !is_readable($s)){
                    //Обработка ошибки, файл не найден
					unset($source[$i]);
				}
			}

			if(!$source){
				return array(
					"status"  => "fail",
					"message" => "FILE_UPLOAD_ERROR",
                    "error"   => "Нет доступных файлов для загрузки"
				);
			}	

			$boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);

			$data .= "--{$boundary}\n";
			$data .= "Content-Disposition: form-data; name=\"apikey\"\n\n{$this->key}\n";
			$data .= "--{$boundary}\n";
			$data .= "Content-Disposition: form-data; name=\"params[object]\"\n\n{$object}\n";
			$data .= "--{$boundary}\n";

			foreach($source as $s){
				$data .= "Content-Disposition: form-data; name=\"upload" . ($is_multiple ? '[]' : '') . "\"; filename=\"" . basename($s) . "\"\n";
				$data .= "Content-Transfer-Encoding: binary\n\n";
				$data .= file_get_contents($s)."\n";
				$data .= "--{$boundary}\n";
			}
		
			$context = stream_context_create(
				array(
					'http' => array(
						'method' => 'POST',
						'header' => 'Content-Type: multipart/form-data; boundary='.$boundary,
						'content' => $data
					)
				)
			);
			
			$response = file_get_contents(
				$this->url . "/utils/upload",
				false,
				$context
			);
			
			return json_decode($response,true);
		}
		
		private function send($sub_url,array $data = array())
		{
			if($this->cache == true){
				$hash = md5($sub_url.serialize($data));
				$cache = IntrumExternalCache::getInstance()->pop($hash);
				
				if($cache !== false){
                    if($this->debug){
                        $this->debugStack[] = array(
                            'url'     => $this->url . $sub_url,
                            'isCache' => true,
                            'params'  => $data,
                            'result'  => $cache
                        );
                    }
					return $cache;
				}
			}
		
			$context = stream_context_create(
				array(
					'http' => array(
						'method' => 'POST',
						'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
						'content' => http_build_query(
							array(
								"apikey" => $this->key,
								"params" => $data
							)
						),
					)
				)
			);
			
			$response = file_get_contents(
				$this->url . $sub_url,
				false,
				$context
			);
            
			$res = json_decode($response,true);
			if(!$res){
                if ($this->debug) {
                    $this->debugStack[] = array(
                        'url'     => $this->url . $sub_url,
                        'isError' => true,
                        'params'  => $data,
                        'result'  => $response
                    );
                }
                return array(
                    'error'=>$response
                ); 
            }
			
			if($this->cache == true){
				IntrumExternalCache::getInstance()->push($hash,$res);
			}
			
            if($this->debug) {
                $this->debugStack[] = array(
                    'url'     => $this->url . $sub_url,
                    'params'  => $data,
                    'result'  => $res
                );
            }

            return $res;
		}
        
        public function webhookPut()
        {
            $input = file_get_contents('php://input');
            $data = json_decode($input, 1);
            return $data;
        }
        
        /* Звонки */
        //Возвращает список возможных соединений
        public function callsGetTrunks()
        {
            return $this->send('/calls/trunks');
        }
        
        public function callsGetPhones()
        {
            return $this->send('/calls/phoneNumbers');
        }
        
        /*Список статусов*/
        public function callsGetStatuses()
        {
            return $this->send('/calls/statuses');
        }
        
        /* Импорт звонка */
        /*
         *  $params {
                uniqueId	    Уникальный идентификатор звонка. 32-х символьная строка
                from	        Номер, с которого совершён звонок
                to	            Номер, на который был совершён звонок
                timestamp	    Время совершения звонка. Unix Timestamp * 1000 ( в микросекундах )
                trunkId	ID      соединения, через которое был совершёл звонок
                callerId	    Caller ID звонящего (обычно совпадает с его номером), не актуален при указанном isIncoming
                isIncoming	    Направление звонка с т.з. CRM системы: true или 1 - входящий, иначе - исходящий
                url	            url записи звонка. Звонок будет скачан и сохранён в CRM системе через некоторое время
                callDuration	Длительность звонка в секундах
                isAnswered	    Был ли ответ на звонок
                customStatus	ID назначенного звонку статуса
         *  }
         */
        public function callsAdd(array $params)
        {
            return $this->send("/calls/import",$params);
        }
        
        /* Импорт звонков */
        /*
         *  $params [
               {
                   uniqueId	        Уникальный идентификатор звонка. 32-х символьная строка
                   from	            Номер, с которого совершён звонок
                   to	            Номер, на который был совершён звонок
                   timestamp	    Время совершения звонка. Unix Timestamp * 1000 ( в микросекундах )
                   trunkId	ID      соединения, через которое был совершёл звонок
                   callerId	        ID звонящего (обычно совпадает с его номером), не актуален при указанном isIncoming
                   isIncoming	    Направление звонка с т.з. CRM системы: true или 1 - входящий, иначе - исходящий
                   url	            url записи звонка. Звонок будет скачан и сохранён в CRM системе через некоторое время
                   callDuration	    Длительность звонка в секундах
                   isAnswered	    Был ли ответ на звонок
                   customStatus	    ID назначенного звонку статуса
                },
                ...
         *  ]
         */
        public function callsAddList(array $params)
        {
            return $this->send("/calls/importall",array(
                'calls' => $params
            ));
        }
        
        /* Обновление информации о звонке
         * $params {
                uniqueId        Уникальный идентификатор звонка, чья запись бует обновлена
                url             url записи звонка. Звонок будет скачан и сохранён в CRM системе через некоторое время
                callDuration	Длительность звонка в секундах
                isAnswered      Был ли ответ на звонок
                customStatus	ID назначенного звонку статуса
         * }
         */
        public function callsUpdate(array $params)
        {
            return $this->send('/calls/update',$params);
        }
        
        /* Получить список звонков по фильтру
         * $params {
         *      fromPhone   Фильтр по номеру с которого звонили
                toPhone     Фильтр по номеру на который звонили
                dateFrom    Искать звонки после даты
                dateTo      Искать звонки до даты
                type        Тип звонков: in, out
                limit       Ограничить ответ количеством записей (макс. 1000)
                page        Совместно с ограничением, определяет страницу выдачи
                orderField  Сортировать по полю (по-умолчанию поле даты,date_time)
                orderType   Направление сортировки: ASK, DESC (по-умолчанию DESK)
         * }
         */
        public function callsGetList(array $params)
        {
            return $this->send('/calls/history',$params);
        }
        
	}
?>