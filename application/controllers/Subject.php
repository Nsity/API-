<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Subject extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }

    public function getmarks($pupil_id = null, $subject_id = null, $period_id = null) {
		if($subject_id == null || $period_id == null || $pupil_id == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {
		    $period = $this->model->getPeriodById($period_id);

		    $start = $period['PERIOD_START'];
		    $finish = $period['PERIOD_FINISH'];

		    $marks =  $this->model->getMarksForSubject($pupil_id, $subject_id, $start, $finish);

		    $arr = array();
		    $i = 0;
		    foreach($marks as $mark) {
			    $arr[$i]["Ид"] = $mark['ACHIEVEMENT_ID'];
				$arr[$i]["Оценка"] = $mark['ACHIEVEMENT_MARK'];
				$arr[$i]["Тип"] = $mark['TYPE_NAME'];
				$arr[$i]["Дата"] = $mark['LESSON_DATE'];
				$arr[$i]['УрокИд'] = $mark['LESSON_ID'];
				$i++;
		    }

		    http_response_code(200);
				echo json_encode(array("result" => array("Оценки" => $arr)), JSON_UNESCAPED_UNICODE);

		}
    }
}

?>