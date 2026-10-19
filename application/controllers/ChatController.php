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

        $chatId = (int)($data['chat_id'] ?? 0);
        $userId = (int)($data['user_id'] ?? 0);
        $message = trim((string)($data['message'] ?? ''));
        $member = $this->db->where('id_chats', $chatId)->where('id_employee', $userId)->count_all_results('chat_participants');
        if (!$member || $message === '' || strlen($message) > 60000) {
            $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Mensaje o participante inválido']));
            return;
        }
        $saved = $this->ChatModel->insertMessage([
            'id_chats' => $chatId, 'id_employee' => $userId, 'message' => $message
        ]);
        $this->output->set_status_header($saved ? 200 : 500)->set_content_type('application/json')->set_output(json_encode([
            'status' => $saved ? 'ok' : 'error', 'id_messages' => $saved ? $this->db->insert_id() : null
        ]));
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
