<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Progress extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }

	public function get($pupil_id = null, $class_id = null)
	{
		if($class_id == null || $pupil_id == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {
		    $subjects = $this->model->getSubjectsClass($class_id);

		    $resArr = array();
		    $k = 0;
		    if(isset($subjects)) {
			    foreach ($subjects as $subject) {
				    $resArr[$k]["ПредметИд"] = $subject['SUBJECTS_CLASS_ID'];

				    $subject_id = $subject['SUBJECTS_CLASS_ID'];

				    $marks = $this->model->getProgressMarks($subject_id, $pupil_id);

				    $arr = array();
				    $i = 0;
					if(isset($marks)) {
						foreach($marks as $mark) {
							$arr[$i]["Ид"] = $mark['PROGRESS_ID'];
							$arr[$i]["Оценка"] = $mark['PROGRESS_MARK'];
							$arr[$i]['ПериодИд'] = $mark['PERIOD_ID'];
							$i++;
						}
					}

					$resArr[$k]['Оценки'] = $arr;
					$k++;
				}
			}

			http_response_code(200);
			echo json_encode(array("result" => array("ИтоговыеОценки" => $resArr)), JSON_UNESCAPED_UNICODE);

	    }
	}
}