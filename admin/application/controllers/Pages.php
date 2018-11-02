<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function index() {

		$data = array(
						

					);

		$data['pages'] = $this->db->get('pages')->result_array();

		foreach($data['pages'] as $key => $value) {
			$data['pages'][$key]['readlink'] = base_url('pages/read/'.$value['page_id']);
			$data['pages'][$key]['updatelink'] = base_url('pages/update/'.$value['page_id']);
			$data['pages'][$key]['deletelink'] = base_url('pages/delete/'.$value['page_id']);
		}


		$this->parser->parse('template/header', $data);
		$this->parser->parse('body', $data);
		$this->parser->parse('template/footer', $data);
		
	
	}


	public function create() {
		if($this->input->post()) {
			$this->form_validation->set_rules('page_headline', 'Headline', 'required|min_length[2]|max_length[150]');
			if($this->form_validation->run()) {

				$page_data = array(
									'page_headline' => $this->input->post('page_headline'),
									'page_content' => $this->input->post('page_content'),
									'page_slug' => url_title($this->input->post('page_headline'), 'dash', TRUE),
									'page_published' => time()
								);

				if(!empty($this->input->post('page_frontpage'))) {
					$this->db->update('pages', array('page_frontpage' => 0));
					$page_data['page_frontpage'] = 1;
				}

				$this->db->insert('pages',$page_data);
			}
		}

		$data = array('validation_errors' => validation_errors());


		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('pages_create', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
		
	}

	public function read($page_id) {

		$this->db->where('page_id', $page_id);
		$data = $this->db->get('pages')->row_array();
		
		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('pages_read', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
	}

	public function update($page_id) {
		if($this->input->post()) {
			$this->form_validation->set_rules('page_headline', 'Headline', 'required|min_length[2]|max_length[150]');
			if($this->form_validation->run()) {
				$page_data = array(
									'page_headline' => $this->input->post('page_headline'),
									'page_content' => $this->input->post('page_content'),
									'page_slug' => url_title($this->input->post('page_headline'), 'dash', TRUE),
									'page_published' => time()	
								  );

				if(!empty($this->input->post('page_frontpage'))) {
					$this->db->update('pages', array('page_frontpage' => 0));
					$page_data['page_frontpage'] = 1;
				}else{
					$page_data['page_frontpage'] = 0;
				 }

				$this->db->where('page_id', $page_id);
				$this->db->update('pages', $page_data);
				redirect('pages');
			}
		}

		$data = $this->db->where('page_id', $page_id)->get('pages')->row_array();

		if($data['page_frontpage'] == 1) {
			$data['check_frontpage'] = "checked='checked'";
		}

		$data['validation_errors'] = validation_errors();

		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('page_update', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
	}

	public function delete($page_id) {

		if($this->input->post()) {
			$this->db->where('page_id', $page_id);
			$this->db->delete('pages');
			redirect('pages');
		}

		$this->db->where('page_id', $page_id);
		$data = $this->db->get('pages')->row_array();

		$data['cancel_link'] = base_url('pages');

		$this->parser->parse('template/header', $data);
		$this->parser->parse('template/topbar', $data);
		$this->parser->parse('template/nav', $data);
		$this->parser->parse('template/mainstart', $data);
		$this->parser->parse('pages_delete', $data);
		$this->parser->parse('template/mainend', $data);
		$this->parser->parse('template/footer', $data);
	}
}
