<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MundialesAdmin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('MundialesModel', 'mm');
        $this->load->helper(['form', 'url']);
    }

    // LIST
    public function index() {
        $data['mundiales'] = $this->mm->getAll();
        $this->load->view('templates/header_blog_admin');
        $this->load->view('admin/mundiales_list', $data);
        $this->load->view('templates/footer');
    }

    // CREATE VIEW
    public function create() {
        $data['countries'] = $this->mm->getCountries();
        $this->load->view('templates/header_blog_admin');
        $this->load->view('admin/mundiales_create', $data);
        $this->load->view('templates/footer');
    }



    // STORE
    public function store() {

        // 🔥 Make sure upload directory exists
        $uploadDir = FCPATH . 'uploads/worldcups/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $config['upload_path'] = $uploadDir;
        $config['allowed_types'] = 'jpg|jpeg|png|webp|svg';
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('background_img')) {
            $data['error'] = $this->upload->display_errors();
            $this->load->view('templates/header_blog_admin');
            $this->load->view('admin/mundiales_create', $data);
            $this->load->view('templates/footer');
            return;
        }

        $file = $this->upload->data();
        $imagePath = 'uploads/worldcups/' . $file['file_name'];

        // ✅ FIXED: Read correct POST inputs
        $insert = [
            'id_countries' => $this->input->post('id_countries'),
            'id_winner'    => $this->input->post('id_winner'),
            'year'           => $this->input->post('year'),
            'background_img' => $imagePath,
            'enable'         => 1
        ];

        $this->mm->insert($insert);

        redirect(base_url('index.php/home'));
    }

    // DISABLE
    public function disable($id) {
        $this->mm->update($id, ['enable' => 0]);
        redirect('index.php/MundialesAdmin');
    }

    // ENABLE
    public function enable($id) {
        $this->mm->update($id, ['enable' => 1]);
        redirect('index.php/MundialesAdmin');
    }
}
