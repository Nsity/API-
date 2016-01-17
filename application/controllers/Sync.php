<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync extends CI_Controller {


	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }

	public function get($pupil_id = null, $class_id = null)
	{
		if($pupil_id == null || $class_id == null) {
			echo json_encode(array('result' => array(0 => array('message' => "Отсутствуют параметры"))), JSON_UNESCAPED_UNICODE);
	    } else {
		    $subjects = $this->model->getSubjectsClass($class_id);
		    $result = array();
			foreach($subjects as $subject) {

			}



		}


		}

	}



}
