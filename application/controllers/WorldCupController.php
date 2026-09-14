<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WorldCupController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('WorldCupModel', 'wc');
        $this->load->helper(['form', 'url']);
    }

    // ============================================================
    //  ADMIN VIEW - CREATE NEW WORLD CUP PAGE
    // ============================================================
    public function create() {
        $this->load->view('templates/header_blog_admin');
        $this->load->view('mundiales/create_worldcup');
        $this->load->view('templates/footer');
    }

    // ============================================================
    //  INSERT NEW WORLD CUP PAGE INTO TABLE
    // ============================================================
    public function store() {

        $year   = $this->input->post('year');
        $host   = $this->input->post('host');
        $winner = $this->input->post('winner');

        // ---------- IMAGE UPLOAD ----------
        $config['upload_path']   = './uploads/worldcups/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['max_size']      = 8000;
        $config['file_name']     = 'worldcup_' . time();

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        $imagePath = null;

        if ($this->upload->do_upload('background_img')) {
            $uploadData = $this->upload->data();
            $imagePath = "uploads/worldcups/" . $uploadData['file_name'];
        } else {
            $imagePath = ""; // (optional) or default img
        }

        // ---------- SAVE TO DB ----------
        $this->wc->createWorldCup([
            'id_countries'   => $host,
            'id_winner'      => $winner,
            'year'           => $year,
            'background_img' => $imagePath,
            'enable'         => 1
        ]);

        redirect('home');
    }

    // ============================================================
    //  PUBLIC WORLD CUP PAGE
    // ============================================================
    public function view($id_mundial) {

        $data['mundial'] = $this->wc->getWorldCup($id_mundial);

        if (!$data['mundial']) {
            show_404();
        }

        $this->load->view('templates/header_blog');
        $this->load->view('mundiales/worldcup_view', $data);
        $this->load->view('templates/footer');
    }
}
