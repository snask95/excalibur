<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Images extends CI_Controller {

	public function index() {

		$data['images'] = $this->db->get('images')->result_array();

		foreach ($data['images'] as $key => $value) {
			$data['images'][$key]['image_file'] = base_url('assets/img/'.$data['images'][$key]['image_file'])
			$data['images'][$key]['updatelink'] = base_url('images/update/'.$data['images'][$key]['image_id']);
			$data['images'][$key]['deletelink'] = base_url('images/delete/'.$data['images'][$key]['image_id']);
		}

		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('images_index', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
	}

	public function create() {

		if($this->input->post()) {

		#File uploading starting
					
			$config['upload_path'] = FCPATH."assets/img/users";
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['encrypt_name'] = TRUE;

			$this->load->library('upload', $config);

			if($this->upload->do_upload('form_file')) {
				$image = array(
								'image_file' => $this->upload->data('file_name'), 
								'image_owner' => $this->session->userdata('user')['user_id'],
								'image_time' => time()
								);

				if(!empty($this->input->post('image_description'))) {
					$image['image_description'] = $this->input->post('image_description');
				}

				$this->db->insert('images', $image);
			}

		#File uploading ending		

		}

		$data = array();
		
		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('images_create', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
	}

	public function read() {
		
		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);	
	}

	public function update($image_id) {

		if($this->input->post()) {
			if(!empty($this->input->post('image_description'))) {
				$image = array(
								'image_description' => $this->input->post('image_description')
							 );

				$this->db->where('image_id', $image_id)->update('images', $image);
				redirect('images/update/', $image_id);
			}
		}

		$this->db->where('image_id', $image_id)->get('images')->row_array();

		$data['image_file'] = base_url('assets/img/'. $data['image_file']);
		
		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('images_update', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);			
	}

	public function delete($image_id) {
		
		$data = $this->db->where('image_id', $image_id)->get('images')->row_array();
		
		if($this->input->post()) {
			$this->db->where('image_id', $image_id)->delete('images');
			unlink(FCPATH. "assets/img/", $data[image_file]);

			redirect('images');
		}

		$data['image_file'] = base_url('assets/img/', $data['image_file']);
		$data['image_file'] = base_url('images');

		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('images_delete', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
	}


}
