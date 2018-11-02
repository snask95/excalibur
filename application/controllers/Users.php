<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	
	}

	public function index() {
	
	}

// Oprettelse af bruger
	public function create() {

		if($this->input->post()) {
			$this->form_validation->set_rules('userEmail', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('userName', 'Fulde navn', 'required');
			$this->form_validation->set_rules('nickname', 'Karakter navn', 'required');
			$this->form_validation->set_rules('userPassword', 'Adgangskode', 'required|min_length[6]|max_lenght[12]');
			$this->form_validation->set_rules('repeatUserPassword', 'Gentag Adgangskode', 'required|matches[userPassword]');


			if($this->form_validation->run()) {
				$user_data = array(
					'user_Email' => $this->input->post('userEmail'),
					'user_Password' => password_hash($this->input->post('userPassword'),PASSWORD_DEFAULT)
				);


				$this->db->insert('users', $user_data);
			}
		}

		$data = array(
			'error_email' => form_error('userEmail'),
			'error_password' => form_error('userPassword'),
			'error_password_repeat' => form_error('repeatUserPassword'),
			'value_email' => ('user_Email')
		);

		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
	}

// Updater bruger informationer
	public function update($user_id = null) {

		if($this->input->post()) {
			$this->form_validation->set_rules('userEmail', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('userName', 'Fulde navn', 'required');
			$this->form_validation->set_rules('nickname', 'Karakter navn', 'required');

			if(!empty($this->input->post(userPassword))) {
			$this->form_validation->set_rules('userPassword', 'Adgangskode', 'required|min_length[6]|max_lenght[12]');
			$this->form_validation->set_rules('repeatUserPassword', 'Gentag Adgangskode', 'required|matches[userPassword]');
			}


			if($this->form_validation->run()) {
				$user_data = array(
					'user_Email' => $this->input->post('userEmail'),
					'user_Password' => password_hash($this->input->post('userPassword'),PASSWORD_DEFAULT)
				);


				$this->db->where('user_id', $user_id)->update('users', $user_data);
			}
		}

		$dbFetch = $this->db->where('user_id', $user_id)->get('users')->row();

		$data = array(
			'error_email' => form_error('userEmail'),
			'error_userName' => form_error('userName'),
			'error_nickname' => form_error('nickname'),
			'error_password' => form_error('userPassword'),
			'error_password_repeat' => form_error('repeatUserPassword'),
			'value_email' => ('user_Email')
		);

		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
		$this->parser->parse();
	}

// Slet brugeren
	public function delete($user_id) {

		$this->db->where('user_id', $user_id)->delete('users');

	}

}
