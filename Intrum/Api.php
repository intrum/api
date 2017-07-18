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
            return "http://" .$this->host . "/files/crm/product/" . $name;
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
		
		public function getSaleTypes()
		{
			return $this->send("/sales/types");
		}
		
		public function getSaleFields()
		{
			return $this->send("/sales/fields");
		}
		
		public function filterSales(array $params)
		{
			return $this->send("/sales/filter",$params);
		}
		
		//вставка
		public function insertSales(array $params)
		{
			return $this->send("/sales/insert",$params);
		}
		
		//обновление
		public function updateSales(array $params)
		{
			return $this->send("/sales/update",$params);
		}
		
		//удаление
		public function deleteSales(array $params)
		{
			return $this->send("/sales/delete",$params);
		}
		
		//дополнения
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
		public function filterCustomers(array $params)
		{
			return $this->send("/purchaser/filter",$params);
		}
		
		//вставка
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
        public function addCustomerComment(array $params) {
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
		
		// загрузчик файлов
		public function uploadFile($object,$source)
		{
			$is_multiple = is_array($source);
			$source = (array) $source;

			foreach($source as $i => $s){
				if(!file_exists($s) or !is_readable($s)){
                    pr($s);
					unset($source[$i]);
				}
			}

			if(!$source){
				return array(
					"status"  => "fail",
					"message" => "FILE_UPLOAD_ERROR"
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
        
	}
?>