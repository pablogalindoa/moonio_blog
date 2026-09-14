<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ChatController extends CI_Controller {

    // CREATE CHAT
    public function create_chat() {

        $this->load->model("ChatModel");

        $group_image = null;

        if (!empty($_FILES['group_image']['name'])) {

            $config['upload_path'] = FCPATH . 'uploads/groups/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['encrypt_name'] = true;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('group_image')) {

                $uploadData = $this->upload->data();
                $group_image = 'uploads/groups/' . $uploadData['file_name'];

            } else {

                echo json_encode([
                    "status" => "error",
                    "message" => $this->upload->display_errors()
                ]);
                return;
            }
        }

        // CREAR CHAT
        $chat_id = $this->ChatModel->createChat([
            'type' => $this->input->post('type'),
            'name' => $this->input->post('name'),
            'group_image' => $group_image
        ]);

        $participants = $this->input->post('participants');
        if (!is_array($participants)) {
            $participants = $this->input->post('participants[]');
        }


        foreach ($participants as $user_id) {

            $this->ChatModel->addParticipant([
                'id_chats' => $chat_id,
                'id_employee' => $user_id
            ]);
        }

        echo json_encode([
            "status" => "ok",
            "chat_id" => $chat_id
        ]);
    }



    // SAVE MESSAGE
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

    // GET MESSAGES
    public function get_messages($chat_id) {

        $this->load->model("ChatModel");

        $messages = $this->ChatModel->getMessages($chat_id);

        echo json_encode($messages);
    }

    public function get_user_chats($user_id) {

        $this->load->model("ChatModel");

        $chats = $this->ChatModel->getUserChats($user_id);

        echo json_encode($chats);
    }

}
