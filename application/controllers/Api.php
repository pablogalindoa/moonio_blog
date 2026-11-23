<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function health() {
        $ok = (bool)$this->db->query('SELECT 1')->row();
        $this->output->set_content_type('application/json')->set_output(json_encode(['ok'=>$ok]));
    }
    public function champions() {
        $this->load->model('ChampionsModel');

        $champions = $this->ChampionsModel->getChampions();
        $most_titles = $this->ChampionsModel->getMostTitles();

        $response = [
            "status" => "success",
            "code" => 200,
            "data" => [
                "champions" => $champions,
                "most_titles" => $most_titles
            ]
        ];

        header("Content-Type: application/json");
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
    public function users() {

        if (!$this->session->userdata("id")) { show_error("Inicia sesión", 401); return; }
        $this->db->where("enable", 1)->select("id, first_name, last_name, profile_picture");

        $users = $this->db->get("employee")->result();

        echo json_encode($users);
    }


}
