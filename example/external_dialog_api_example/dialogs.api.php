<?php
namespace Intrum;

class DialogsApi{

	/**
		@__construct

		@param $host host crm intrum
		@param $apikey API ключ
	*/

	public function __construct ($host , $apikey) {
		$this->host = $host;
		$this->key = $apikey;
	}

	private function request ($type , $params = null) {
		$url = "http://{$this->host}:81/sharedapi/externaldialogs/$type";
		$request = array(
			'apikey' => $this->key ,
			'params' => $params
		);
		$ch = curl_init($url);
		curl_setopt_array($ch , array(
			CURLOPT_URL            => $url ,
			CURLOPT_POST           => true ,
			CURLOPT_RETURNTRANSFER => true ,
			CURLOPT_CUSTOMREQUEST  => "POST" ,
			CURLOPT_POSTFIELDS     => http_build_query($request)
		));
		$responseData = json_decode(curl_exec($ch));
		curl_close($ch);
		return $responseData;
	}

	/**
		@method createDialog

			Создание нового диалога

		@param $customer - id клиента в системе CRM (Если нужно привязать диалог к клиенту)
		@param $dialog - Данные канала коммуникации , (array('id' => 'ид канала коммуникации') или array('name' => "Название нового канала коммуникации" , "employees" => array(1,2,3 , список id сотрудников канала)))

		@return StdClass('id' => id созданного клиента (НЕ в CRM) , 'clientkey' => ключ для авторизации)
			Если создается новый канал то в ответе будет присутствовать dialogid - id созданного канала коммуникации

	*/

	public function createDialog($customer = 0 , $dialog = null) {
		$params = array(
			'customer' => $customer
		);
		$r = false;
		if ($dialog !== null) {
			if (isset($dialog['id'])) {
				$params['dialog'] = array('id' => $dialog['id']);
			} else {
				if (isset($dialog['name']) || isset($dialog['name']['employees'])) {
					$params['dialog'] = $dialog;
				}
			}
		}
		$response = $this->request('insert' , $params);
		if ($response->status == 'success') {
			$r = $response->data;
		}
		return $r;
	}

	/**
		@method getTypes

			Получение списка доступных каналов коммуникаций

		@param $page - страница (0 - первая)
		@param $count - количество

		@return StdClass ('list' => array() //Список)
	*/

	public function getTypes ($page = 0 , $count = 10) {
		$r = false;
		$params = array(
			'page' => $page ,
			'count' => $count
		);
		$response = $this->request('dialogtypeslist' , $params);
		if ($response->status == 'success') {
			$r = $response->data;
		}
		return $r;
	}

	/**
		@method loadHistory

			Загрузка истроиии переписки

		@param $group - id группы диалога
		@param $client - id клиента
		@param $date - timestamp с которого загружать историю
		@param $page - страница (0 - первая)
		@param $count - количество сообщений

		@return StdClass ('total' => 9 // Всего сообщений , list => array() // Массив с сообщениями)
	*/

	public function loadHistory ($group , $client , $date , $page = 0 , $count = 20) {
		$r = false;
		$params = array(
			'group' => $group ,
			'client' => $client ,
			'date' => $date ,
			'page' => $page ,
			'count' => $count
		);
		// print_r($params);
		$response = $this->request('history' , $params);
		if ($response->status == 'success') {
			$r = $response->data;
		}
		return $r;
	}
}
?>
