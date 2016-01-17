<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lesson extends CI_Controller {


	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }


	public function get($pupil_id = null, $subject_id = null, $day = null, $time = null)
	{
		if($subject_id == null || $pupil_id == null || $day == null || $time == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {

		    $day = date("Y-m-d", strtotime($day));
		    $lesson = $this->model->getLessonByDate($subject_id, $day, $time);
		    if(isset($lesson)) {

			    $homework = $lesson['LESSON_HOMEWORK'] == null ? "" : $lesson['LESSON_HOMEWORK'];
			    $status = $lesson['LESSON_STATUS'];
			    $lesson_id = $lesson['LESSON_ID'];
			    $lesson_date = $lesson['LESSON_DATE'];
			    $subject_id = $lesson['SUBJECTS_CLASS_ID'];
			    $theme = $lesson['LESSON_THEME'];
			    $time_id = $lesson['TIME_ID'];


			    $pass = $this->model->getPass($lesson_id, $pupil_id)['ATTENDANCE_PASS'] == null ? "" : $this->model->getPass($lesson_id, $pupil_id)['ATTENDANCE_PASS'] ;
				$note = $this->model->getNote($lesson_id, $pupil_id)['NOTE_TEXT'] == null ? "" : $this->model->getNote($lesson_id, $pupil_id)['NOTE_TEXT'];

				$marks = $this->model->getMarks($lesson_id, $pupil_id);
				$arr = array();
				$i = 0;
				if(isset($marks)) {
					foreach($marks as $mark) {
						$arr[$i]["Ид"] = $mark['ACHIEVEMENT_ID'];
						$arr[$i]["Оценка"] = $mark['ACHIEVEMENT_MARK'];
						$arr[$i]["Тип"] = $mark['TYPE_NAME'];
						$i++;
					}
				}

				http_response_code(200);
				echo json_encode(array("result" => array("Ид" => $lesson_id, "ДомашнееЗадание" => $homework, "Тема" => $theme,
				"ВремяИд" => $time_id, "ПредметИд" => $subject_id, "Дата" => $lesson_date, "Статус" => $status,
									   "Пропуск" => $pass, "Замечание" => $note, "Оценки" => $arr)), JSON_UNESCAPED_UNICODE);

		    } else {
			    header("HTTP/1.0 400 Bad Request");
				echo json_encode(array('Exception' => array('Message' => "Отсутствуют данные по учебному занятию")), JSON_UNESCAPED_UNICODE);
		    }
		}
	}

	/*public function gethomework($class_id = null, $offset = 1, $subject_id = null) {
		if($class_id == null || $offset == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {

		    $num = 4;

		    $arr = array();
		    $i = 0;

		    $lessons = $this->model->getHomeworksForClass($class_id, $num, $offset * $num - $num);

		    foreach($lessons as $lesson) {
			    $arr[$i]["ДомашнееЗадание"] = $lesson['LESSON_HOMEWORK'];
			    $arr[$i]["Статус"] = $lesson['LESSON_STATUS'];
			    $arr[$i]["УрокИд"] = $lesson['LESSON_ID'];
			    $arr[$i]["Дата"] = $lesson['LESSON_DATE'];
			    $arr[$i]["ПредметИд"] = $lesson['SUBJECTS_CLASS_ID'];
			    $arr[$i]["Тема"] = $lesson['LESSON_THEME'];
			    $arr[$i]["ВремяИд"] = $lesson['TIME_ID'];
			    $i++;
		    }

			http_response_code(200);
			echo json_encode(array("result" => array("Уроки" => $arr)), JSON_UNESCAPED_UNICODE);
		}
	}
*/

	public function gethomework($class_id = null, $pupil_id = null, $begin_date = null, $end_date = null) {
		$this->load->database();
		if($class_id == null || $pupil_id == null || $begin_date == null || $end_date == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {

		    $subjects = $this->model->getSubjectsClass($class_id);

		    $arr = array();
		    $i = 0;
		    if(isset($subjects)) {
			    foreach ($subjects as $subject) {
				    $subject_id = $subject['SUBJECTS_CLASS_ID'];

				    $lessons = $this->model->getHomeworksForSubject($subject_id, $begin_date, $end_date);

				    foreach($lessons as $lesson) {
					    $arr[$i]["ДомашнееЗадание"] = $lesson['LESSON_HOMEWORK'];
					    $arr[$i]["Статус"] = $lesson['LESSON_STATUS'];
					    $arr[$i]["Ид"] = $lesson['LESSON_ID'];
					    $arr[$i]["Дата"] = $lesson['LESSON_DATE'];
					    $arr[$i]["ПредметИд"] = $lesson['SUBJECTS_CLASS_ID'];
					    $arr[$i]["Тема"] = $lesson['LESSON_THEME'];
					    $arr[$i]["ВремяИд"] = $lesson['TIME_ID'];

					    $arr[$i]["Пропуск"] = $this->model->getPass($lesson['LESSON_ID'], $pupil_id)['ATTENDANCE_PASS'] ==
					    null ? "" : $this->model->getPass($lesson['LESSON_ID'], $pupil_id)['ATTENDANCE_PASS'];

					    $arr[$i]["Замечание"] = $this->model->getNote($lesson['LESSON_ID'], $pupil_id)['NOTE_TEXT'] ==
					    null ? "" : $this->model->getNote($lesson['LESSON_ID'], $pupil_id)['NOTE_TEXT'];

					    $marks = $this->model->getMarks($lesson['LESSON_ID'], $pupil_id);

					    $arrRes = array();
					    $k = 0;
					    if(isset($marks)) {
						    foreach($marks as $mark) {
							    $arrRes[$k]["Ид"] = $mark['ACHIEVEMENT_ID'];
							    $arrRes[$k]["Оценка"] = $mark['ACHIEVEMENT_MARK'];
							    $arrRes[$k]["Тип"] = $mark['TYPE_NAME'];
							    $k++;
							}
					    }

					    $arr[$i]["Оценки"] = $arrRes;

					    $i++;
					}
				}
			}

			http_response_code(200);
			echo json_encode(array("result" => array("Уроки" => $arr)), JSON_UNESCAPED_UNICODE);
		}

	}
}
