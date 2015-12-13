<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function index()
	{

		$this->load->model('model', 'model');
		$subjects = $this->model->selectAll();

	    echo "Hello";
	    echo count($subjects);
	}
}
