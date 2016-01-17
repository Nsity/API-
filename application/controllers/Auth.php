<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Controller {


	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }


	public function login($login = null, $password = null)
	{
		if($login == null || $password == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {
			$user = $this->model->getPupil($login, md5($password));
			if (isset($user)) {
				$result = array('Ид' => $user['PUPIL_ID'],
												  'КлассИд' => $user['CLASS_ID'],
												  'ФИО' => $user['PUPIL_NAME'],
												  'КлассНаименование' => $user['CLASS_NUMBER']." ".$user['CLASS_LETTER']);
				http_response_code(200);
				echo json_encode($result, JSON_UNESCAPED_UNICODE);
			} else {
				//http_response_code(400);
				header("HTTP/1.0 400 Bad Request");
				echo json_encode(array('Exception' => array('Message' => "Неверный логин или пароль")), JSON_UNESCAPED_UNICODE);

			}

		}

	}


	public function register($pupil_id = null, $regId = null) {
		if($pupil_id == null || $regId == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {

		}
	}

}
