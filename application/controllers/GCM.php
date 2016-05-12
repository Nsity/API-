<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class GCM extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }


    public function register($pupil_id = null, $token = null) {
	    if($pupil_id == null || $token == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {
		    $this->model->addGCMDevice($pupil_id, $token);
		    echo("{}");

		}

    }

    public function unregister($pupil_id = null, $token = null) {
	    if($pupil_id == null || $token == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {
		    $this->model->deleteGCMDevice($pupil_id, $token);
		    echo("{}");

		}

    }
}

?>