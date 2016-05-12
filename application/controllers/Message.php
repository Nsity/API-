<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Message extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }


    public function create($pupil_id, $teacher_id) {
	    $text = $_POST['ТекстСообщения'];
		$date = $_POST['ДатаСообщения'];

	    $message_id = $this->model->addMessage($pupil_id, $teacher_id, $text, $date, 'PUPIL', 'TEACHER');

	    http_response_code(200);
		echo json_encode(array("result" => array("Ид" => $message_id)), JSON_UNESCAPED_UNICODE);
    }


    public function getlast($pupil_id = null) {
	    if($pupil_id == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {
		    $teachers = $this->model->getConversations($pupil_id);

		    $resConversations = array();

		    $arr = array();
		    $i = 0;

		    foreach ($teachers as $teacher) {
			    $teacher_id = $teacher['TEACHER_ID'];
			    $message = $this->model->getLastMessageInConversation($pupil_id, $teacher_id);

			    $arr[$i]["УчительИд"] = $teacher_id;
			    $arr[$i]["ТестСообщения"] = $message['MESSAGE_TEXT'];
			    $arr[$i]["ДатаСообщения"] = $message['MESSAGE_DATE'];
			    $arr[$i]["Ид"] = $message['PUPILS_MESSAGE_ID'];
			    $arr[$i]["СообщениеИд"] = $message['MESSAGE_ID'];
			    $arr[$i]["КоличествоНовых"] = $this->model->getNewMessages($pupil_id, $teacher_id)['COUNT'];
			    $i++;
		    }

		    http_response_code(200);
			echo json_encode(array("result" => array("ПоследниеСообщения" => $arr)), JSON_UNESCAPED_UNICODE);

		}
    }


}