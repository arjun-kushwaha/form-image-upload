	#craete article
	public function create_post() {

		$this->load->library('form_validation');
		$this->form_validation->set_rules('category_id','Category','trim|required');
		$this->form_validation->set_rules('title','Title', 'required');
		$this->form_validation->set_rules('description','Description', 'required');
		//$config['upload_path']   = './home/project/ticketing/public/uploads';
		$config['upload_path']    =   './assets/images/userimage';
		$config['allowed_types'] = 'gif|jpg|png';
		//$config['encrypt_name'] = true;
		$this->load->library('upload', $config); 
		if ($this->form_validation->run() && $this->upload->do_upload('image')){
					
					$data = array();
					$img = $this ->upload->data(); 
					$form_array['category_id'] =  $this->input->post('category_id');

					#fetching current login user id and passing in user_data array
					$user_data = $this->User_model->get_profile_id($this->session->userdata('id'));

					#assigning current user user_id to array
					$form_array['user_id'] = $user_data[0]['id'];
					$form_array['title'] =  $this->input->post('title');
					$this->load->model('admin/Category_model');

					#creating slug from get_slug function and assigning to array
					$slug = $this->Category_model->get_slug($this->input->post('title'));
					$form_array['slug'] =  $slug;
					$form_array['body'] =  $this->input->post('description');
					$form_array['post_image'] = $img['orig_name'];

					#capturing current time and date
					$form_array['created_at'] = date('y-m-d H:i:s');
					$form_array['image_type'] = $img['file_ext']; 
					//echo "<pre>";print_r($data); die;

					$this->load->model('admin/Post_model');
					$this->Post_model->add_post($form_array);
					$this->session->set_flashdata('success','Successfully created');
					redirect(base_url().'admin/Post_ctrl/create_post');

		}
		else{

		$data['title'] = 'Create New Article';
		$data['heading'] = 'admin/Post_ctrl';
		$data['desc'] = 'create_post';

		$this->load->model('admin/Category_model');
		$cat = $this->Category_model->fetchCategories();
		$data['cat'] = $cat; 
		$this->load->view('template/head');
		$this->load->view('template/header');
		$this->load->view('template/navigation');
		$this->load->view('template/breadcrumb',$data);
		$this->load->view('blog/article/create',$data);
	}
	}
