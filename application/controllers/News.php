<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class News extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }


    public function get($offset = 1) {
	    if($offset == 0 ) {
			header("HTTP/1.0 400 Bad Request");
			echo json_encode(array('Exception' => array('Message' => "Неверный параметр")), JSON_UNESCAPED_UNICODE);
	    } else {
			$num = 6;
			$news = $this->model->getNews($num, $offset * $num - $num);

			$arrNews = array();

			foreach ($news as $row) {
				$arrNews[] = array("УчительИд" => $row['TEACHER_ID'],
				"Текст" => $row['NEWS_TEXT'],
				"Дата" => $row['NEWS_TIME'],
				"Ид" => $row['NEWS_ID'],
				"Тема" => $row['NEWS_THEME']);
			}

			http_response_code(200);
			//sleep(10);
			echo json_encode(array("result" => array("Новости" => $arrNews)), JSON_UNESCAPED_UNICODE);
		}
    }

}

?>