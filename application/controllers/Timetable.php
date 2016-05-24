<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Timetable extends CI_Controller {


	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }


	public function get($class_id = null, $pupil_id = null)
	{
		if($class_id == null && $pupil_id == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {

		    $arrResult = array();
		    $arrTimes = array();

		    $result = $this->model->getTimes();
		    foreach ($result as $row) {
			    $arrTimes[] = array("Ид" => $row['TIME_ID'], "ВремяНачала" => $row['TIME_START'], "ВремяОкончания" => $row['TIME_FINISH']);
			}

			$arrPeriods = array();

			$result = $this->model->getBorders($class_id);
			foreach ($result as $row) {
				 $arrPeriods[] = array("Ид" => $row['PERIOD_ID'], "Наименование" => $row['PERIOD_NAME'],
				 "ВремяНачала" => $row['PERIOD_START'], "ВремяОкончания" => $row['PERIOD_FINISH']);

			}


			$arrSubjects = array();
			$result = $this->model->getSubjectsClass($class_id);
		    foreach ($result as $row) {
			    $arrSubjects[] = array("Ид" => $row['SUBJECTS_CLASS_ID'], "Наименование" => $row['SUBJECT_NAME'], "УчительИд" => $row['TEACHER_ID']);
			}

			$arrTeachers = array();

			$result = $this->model->getTeachers($class_id);
			foreach ($result as $row) {
			    $arrTeachers[] = array("Ид" => $row['TEACHER_ID'], "Имя" => $row['TEACHER_NAME']);
			}



			$messages = $this->model->getAllMessages($pupil_id);

		    $arrMessages = array();

		    foreach ($messages as $message) {
			    $arrMessages[] = array("УчительИд" => $message['TEACHER_ID'],
			    "ТекстСообщения" => $message['MESSAGE_TEXT'],
			    "ДатаСообщения" => $message['MESSAGE_DATE'],
			    "Ид" => $message['PUPILS_MESSAGE_ID'],
			    "ТипСообщения" => $message['MESSAGE_FOLDER'],
			    "Прочтено" => $message["MESSAGE_READ"]);
		    }

			/*$arrProgress = array();

		    $k = 0;
		    if(isset($result)) {
			    foreach ($result as $subject) {
				    $arrProgress[$k]["ПредметИд"] = $subject['SUBJECTS_CLASS_ID'];
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

					$arrProgress[$k]['Оценки'] = $arr;
					$k++;
				}
			}*/

			$days = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');

			$arrTimetable = array();
		    for ($i = 0; $i < 6; $i++) {
			    $result = $this->model->getTimetable($class_id, $i+1);
			    $y = 0;
			    foreach ($result as $row) {
				   $arrTimetable[$i][$y] = array(
				   "Ид" => $row['TIMETABLE_ID'],
				   "ВремяИд"=> $row['TIME_ID'],
				   "ДеньИд" => $row['DAYOFWEEK_ID'],
				   "ПредметИд"=>$row['SUBJECTS_CLASS_ID'],
				   "КабинетНаименование" => $row['ROOM_NAME'] == null ? "" : $row['ROOM_NAME']);
				   $y++;
				}
			}

			if (isset($arrResult)) {
				http_response_code(200);
				echo json_encode(array("result" => array(
				"Время" => $arrTimes,
				"Предметы" => $arrSubjects,
				"Расписание" => $arrTimetable,
				"Учителя" => $arrTeachers,
				"Периоды" => $arrPeriods,
				"Сообщения" => $arrMessages)), JSON_UNESCAPED_UNICODE);
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
