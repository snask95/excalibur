<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function index() {

		$data = array(
						'create_link' => base_url('users/create')

					);

		$data['users'] = $this->db->get('users')->result_array();

		foreach($data['users'] as $key => $value) {
			$data['users'][$key]['readlink'] = base_url('users/read/'.$value['user_id']);
			$data['users'][$key]['updatelink'] = base_url('users/update/'.$value['user_id']);
			$data['users'][$key]['deletelink'] = base_url('users/delete/'.$value['user_id']);
			if($data['users'][$key]['user_level'] == 1) {
				$data['users'][$key]['user_level'] = "<img src='".base_url('assets/img/admin.png')."'/>";
			}else{
				$data['users'][$key]['user_level'] = "";
			}
		}


		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('user_list', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
	
	}


	public function create() {
		#if(!$this->session->user) {
		#	redirect('users/login');
		#}

		if($this->input->post()) {
			$this->form_validation->set_rules('user_realname', 'Real name', 'required|min_length[2]|max_length[150]');
			$this->form_validation->set_rules('user_email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('user_password', 'Password', 'required|min_length[8]');
			$this->form_validation->set_rules('user_options', 'Options', 'required');
				if($this->form_validation->run()) {
				
					$user_data = array(
										'user_email' => $this->input->post('user_email'),
										'user_password' => password_hash($this->input->post('user_password'),PASSWORD_DEFAULT),
										'user_realname' => $this->input->post('user_realname'),
										'user_level' => $this->input->post('user_options')
								 	 );

					$this->db->insert('users',$user_data);

				#File uploading starting
					
						$config['upload_path'] = FCPATH."assets/img/users";
						$config['allowed_types'] = 'gif|jpg|png|jpeg';
						$config['encrypt_name'] = TRUE;

						$this->load->library('upload', $config);

						if($this->upload->do_upload('form_file')) {
							$profilepicture = array(
													'pp_uid' => $this->db->insert_id('users'), 
													'pp_image' => $this->upload->data('file_name')
													);

							$this->db->insert('profilepictures', $profilepicture);
						}

				#File uploading ending		

					redirect('users');	
				}
		}

		$data = array('validation_errors' => validation_errors());


		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('create_user', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
		
	}

	public function read($user_id) {
		if(!$this->session->user){
			redirect('users/login');
		}

		$this->db->where('user_id', $user_id);
		$data = $this->db->get('users')->row_array();
		
		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('read_user', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);		
	}

	public function update($user_id) {
		if(!$this->session->user){
			redirect('users/login');
		}		

		if($this->input->post()) {
			$this->form_validation->set_rules('user_realname', 'Real name', 'required|min_length[2]|max_length[150]');
			$this->form_validation->set_rules('user_email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('user_password', 'Password', 'required|min_length[8]');
			$this->form_validation->set_rules('user_options', 'Options', 'required');
				if($this->form_validation->run()) {
				
					$user_data = array(
										'user_email' => $this->input->post('user_email'),
										'user_password' => password_hash($this->input->post('user_password'),PASSWORD_DEFAULT),
										'user_realname' => $this->input->post('user_realname'),
										'user_level' => $this->input->post('user_options')
								 	 );	

					#File uploading starting
					
						$config['upload_path'] = FCPATH."assets/img/users";
						$config['allowed_types'] = 'gif|jpg|png|jpeg';
						$config['encrypt_name'] = TRUE;

						$this->load->library('upload', $config);

						if($this->upload->do_upload('form_file')) {
							$profilepicture = array(
													'pp_uid' => $this->db->insert_id('users'), 
													'pp_image' => $this->upload->data('file_name')
													);

							$this->db->insert('profilepictures', $profilepicture);
						}

				#File uploading endings

					$this->db->where('user_id', $user_id);
					$this->db->update('users', $user_data); 

					redirect('users');
				}
		}

		$data = $this->db->where('user_id', $user_id)->get('users')->row_array();

		$data['validation_errors'] = validation_errors();

		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('update_user', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
	}
				
	

	public function delete($user_id) {
		if(!$this->session->user){
			redirect('users/login');
		}

		if($this->input->post()) {
			$this->db->where('user_id', $user_id);
			$this->db->delete('users');
			redirect('users');
		}

		$this->db->where('user_id', $user_id);
		$data = $this->db->get('users')->row_array();

		$data['cancel_link'] = base_url('users');

		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('delete_user', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
	}

	public function login() {

		if($this->input->post()) {
			$this->form_validation->set_rules('user_email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('user_password', 'Password', 'required');
			if($this->form_validation->run()) {
				$user = $this->db->where('user_email', $this->input->post('user_email'))->get('users')->row_array();

				if(password_verify($this->input->post('user_password'),$user['user_password'])) {
					unset($user['user_password']);
					$this->session->set_userdata('user', $user);
					redirect();
				}
			} 
		}

		$data['validation_errors'] = validation_errors();

		$this->parser->parse('login_user', $data);
	}

	public function upload() {
		if($this->input->post()) {
			$config['upload_path'] = FCPATH."assets/img/users";
			$config['allowed_types'] = 'gif|jpg|png';
			$config['encrypt_name'] = TRUE;

			$this->load->library('upload', $config);

			if($this->upload->do_upload('form_file')) {
				echo "<pre>";

				print_r($this->upload->data('file_name'));

				echo "</pre>";
			}else{
				echo $this->upload->display_errors();
			}
		}

		$this->load->view('upload');
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('users/login');
	}

}
