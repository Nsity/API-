<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('model', 'model');
    }


	public function login($login, $password)
	{
		$subjects = $this->model->getPupil($login, md5($password));
	    echo count($subjects);
	}



}
