<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

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
    public function user_chats($user_id) {

        $this->load->model("ChatModel");

        $chats = $this->ChatModel->getUserChats($user_id);

        header("Content-Type: application/json");
        echo json_encode($chats);
    }

    public function messages($chat_id) {

        $this->load->model("ChatModel");

        $messages = $this->ChatModel->getMessages($chat_id);

        header("Content-Type: application/json");
        echo json_encode($messages);
    }

    public function save_message() {

        $data = $this->input->post();

        if (!$data) {
            echo json_encode([
                "status" => "error",
                "message" => "No data received"
            ]);
            return;
        }

        $this->load->model("ChatModel");

        $this->ChatModel->insertMessage([
            'id_chats' => $data['chat_id'],
            'id_employee' => $data['user_id'],
            'message' => $data['message']
        ]);

        echo json_encode(["status" => "ok"]);
    }
    public function users() {

        $this->db->select("id, first_name, last_name, profile_picture");

        $users = $this->db->get("employee")->result();

        echo json_encode($users);
    }


}
