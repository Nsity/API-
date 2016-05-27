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

	    $message = $this->model->getMessageById($message_id);

	    http_response_code(200);
		echo json_encode(array("result" =>
		                 array("Ид" => $message_id,
		                       "УчительИд" => $message['TEACHER_ID'],
		                       "ТекстСообщения" => $message['MESSAGE_TEXT'],
		                       "ДатаСообщения"=> $message['MESSAGE_DATE'],
		                       "ТипСообщения" =>  $message['MESSAGE_FOLDER'],
		                       "Прочтено" => $message["MESSAGE_READ"])), JSON_UNESCAPED_UNICODE);
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
			    $arr[$i]["ТекстСообщения"] = $message['MESSAGE_TEXT'];
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


    public function get($pupil_id = null, $teacher_id = null) {
	    if($pupil_id == null || $teacher_id == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {
		    $messages = $this->model->getMessagesInConversation($pupil_id, $teacher_id);

		    $resMessages = array();

		    $arr = array();
		    $i = 0;

		    foreach ($messages as $message) {
			    $arr[$i]["УчительИд"] = $message['TEACHER_ID'];
			    $arr[$i]["ТекстСообщения"] = $message['MESSAGE_TEXT'];
			    $arr[$i]["ДатаСообщения"] = $message['MESSAGE_DATE'];
			    $arr[$i]["Ид"] = $message['PUPILS_MESSAGE_ID'];
			    $arr[$i]["ТипСообщения"] = $message['MESSAGE_FOLDER'];
			    $arr[$i]["Прочтено"] = $message["MESSAGE_READ"];
			    $i++;
		    }

		    http_response_code(200);
			echo json_encode(array("result" => array("Сообщения" => $arr)), JSON_UNESCAPED_UNICODE);

		}

    }


     public function getall($pupil_id = null) {
	    if($pupil_id == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {
		    $messages = $this->model->getAllMessages($pupil_id);

		    $arr = array();
		    $i = 0;

		    foreach ($messages as $message) {
			    $arr[$i]["УчительИд"] = $message['TEACHER_ID'];
			    $arr[$i]["ТекстСообщения"] = $message['MESSAGE_TEXT'];
			    $arr[$i]["ДатаСообщения"] = $message['MESSAGE_DATE'];
			    $arr[$i]["Ид"] = $message['PUPILS_MESSAGE_ID'];
			    $arr[$i]["ТипСообщения"] = $message['MESSAGE_FOLDER'];
			    $arr[$i]["Прочтено"] = $message["MESSAGE_READ"];
			    $i++;
		    }

		    http_response_code(200);
			echo json_encode(array("result" => array("Сообщения" => $arr)), JSON_UNESCAPED_UNICODE);

		}

    }


    public function read($pupil_id = null, $teacher_id = null) {
	    if($pupil_id == null || $teacher_id == null) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Отсутствуют параметры")), JSON_UNESCAPED_UNICODE);
	    } else {
		    $this->model->readConversation($pupil_id, $teacher_id, 'PUPIL', 'TEACHER');
		    echo ('{}');
		}
    }



}