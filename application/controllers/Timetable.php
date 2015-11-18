<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Timetable extends CI_Controller {


	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }


	public function get($class_id = null)
	{
		if($class_id == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {

		    $arrResult = array();
		    $arrTimes = array();

		    $result = $this->model->getTimes();
		    foreach ($result as $row) {
			    $arrTimes[] = array("Ид" => $row['TIME_ID'], "ВремяНачала" => $row['TIME_START'], "ВремяОкончания" => $row['TIME_FINISH']);
			}


			$arrSubjects = array();
			$result = $this->model->getSubjectsClass($class_id);
		    foreach ($result as $row) {
			    $arrSubjects[] = array("Ид" => $row['SUBJECTS_CLASS_ID'], "Наименование" => $row['SUBJECT_NAME']);
			}

			$days = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');

			$arrTimetable = array();
		    for ($i = 0; $i < 6; $i++) {
			    $result = $this->model->getTimetable($class_id, $i+1);
			    $y = 0;
			    foreach ($result as $row) {
				   $arrTimetable[$i][$y] = array("Ид" => $row['TIMETABLE_ID'], "ВремяИд"=> $row['TIME_ID'], "ПредметИд"=>$row['SUBJECTS_CLASS_ID'], "КабинетНаименование" => $row['ROOM_NAME']);
				   $y++;
				}
			}

			if (isset($arrResult)) {
				http_response_code(200);
				echo json_encode(array("result" => array("Время" => $arrTimes, "Предметы" => $arrSubjects, "Расписание" => $arrTimetable)), JSON_UNESCAPED_UNICODE);
			} else {
				//http_response_code(400);
				header("HTTP/1.0 400 Bad Request");
				echo json_encode(array('Exception' => array('Message' => "Отсутствует расписание")), JSON_UNESCAPED_UNICODE);

			}

		}

	}


	private function _getTimetableForDay($class_id, $day) {
		$result = $this->pupil->getTimetable($class_id, $day);

		$arrResult = array();
		foreach ($result as $row) {
			$arrResult[] = array("start"=> $row['TIME_START'],"finish"=>$row['TIME_FINISH'],
			"name"=>$row['SUBJECT_NAME'], "room" => $row['ROOM_NAME']);
		}
		return $arrResult;
	}
}
