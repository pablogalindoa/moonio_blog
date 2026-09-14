<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LocationController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('LocationModel', 'location');
    }

    public function save()
    {
        $id = $this->session->userdata('id');

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'No session']);
            return;
        }

        $data = [
            'user_id' => $id,
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude')
        ];

        $saved = $this->location->saveLocation($data);

        echo json_encode([
            'status' => $saved ? 'ok' : 'error'
        ]);
    }
}