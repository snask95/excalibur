<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index() {

		$data = array();

		$this->parser->parse('template/header', $data);
		$this->parser->parse('body', $data);
		$this->parser->parse('template/footer', $data);
	}
}	


