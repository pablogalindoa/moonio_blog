<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ChatController extends CI_Controller {

    private function json($data, $status = 200) {
        return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode($data));
    }
    private function member($chatId) {
        return $this->session->userdata('id') && $this->db->where('id_chats', (int)$chatId)->where('id_employee', (int)$this->session->userdata('id'))->count_all_results('chat_participants');
    }
    public function socket_session() {
        $id = (int)$this->session->userdata('id');
        if (!$id) return $this->json(['status'=>'error'], 401);
        $rooms = $this->db->select('id_chats')->where('id_employee', $id)->get('chat_participants')->result();
        return $this->json(['id'=>$id, 'name'=>$this->session->userdata('first_name'), 'chats'=>array_map(function($r){return (int)$r->id_chats;}, $rooms)]);
    }
    public function mark_read($chatId) {
        if (!$this->member($chatId)) return $this->json(['status'=>'error'], 403);
        $id = max(0, (int)$this->input->post('message_id'));
        // Only acknowledge a message that really belongs to this chat.
        if ($id && $this->db->where('id_messages', $id)->where('id_chats', (int)$chatId)->count_all_results('messages')) {
            $this->db->where('id_chats', (int)$chatId)->where('id_employee', (int)$this->session->userdata('id'))->set('last_read_message_id', 'GREATEST(last_read_message_id, '.$id.')', false)->update('chat_participants');
        }
        return $this->json(['status'=>'ok']);
    }
    public function create_chat() {
        $creator = (int)$this->session->userdata('id');
        if (!$creator) return $this->json(['status'=>'error', 'message'=>'Inicia sesión.'], 401);
        $participants = $this->input->post('participants');
        $participants = array_values(array_unique(array_merge([$creator], array_map('intval', is_array($participants) ? $participants : []))));
        $type = $this->input->post('type');
        $name = trim((string)$this->input->post('name'));
        if (!in_array($type, ['privado','grupo'], true) || ($type === 'privado' && count($participants) !== 2) || ($type === 'grupo' && (count($participants) < 3 || $name === '')) || strlen($name) > 100) {
            return $this->json(['status'=>'error', 'message'=>'Privado: 2 usuarios. Grupo: nombre y mínimo 3 integrantes.'], 400);
        }
        if ($this->db->where_in('id', $participants)->where('enable', 1)->count_all_results('employee') !== count($participants)) {
            return $this->json(['status'=>'error', 'message'=>'Participantes inválidos.'], 400);
        }
        $group_image = null;
        if (!empty($_FILES['group_image']['name'])) {
            $this->load->library('upload', ['upload_path'=>FCPATH.'uploads/groups/', 'allowed_types'=>'jpg|jpeg|png|gif', 'max_size'=>2048, 'encrypt_name'=>true]);
            if (!$this->upload->do_upload('group_image')) return $this->json(['status'=>'error','message'=>strip_tags($this->upload->display_errors())], 400);
            $group_image = 'uploads/groups/'.$this->upload->data('file_name');
        }
        $this->load->model('ChatModel');
        $this->db->trans_start();
        $chatId = $this->ChatModel->createChat(['type'=>$type, 'name'=>$name, 'group_image'=>$group_image]);
        foreach ($participants as $id) $this->ChatModel->addParticipant(['id_chats'=>$chatId, 'id_employee'=>$id]);
        $this->db->trans_complete();
        return $this->json(['status'=>$this->db->trans_status() ? 'ok' : 'error', 'chat_id'=>$chatId]);
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
        $userId = (int)$this->session->userdata('id');
        $message = trim((string)($data['message'] ?? ''));
        $member = $this->db->where('id_chats', $chatId)->where('id_employee', $userId)->count_all_results('chat_participants');
        if (!$member || $message === '' || strlen($message) > 60000) {
            $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Mensaje o participante inválido']));
            return;
        }
        $encrypted = ($data['encrypted'] ?? '0') === '1';
        $stored = $message;
        if ($encrypted) {
            $this->load->library('MessageCipher');
            try { $stored = $this->messagecipher->encrypt($message); }
            catch (RuntimeException $e) { return $this->json(['status'=>'error', 'message'=>$e->getMessage()], 503); }
        }
        if (strlen($stored) > 65000) return $this->json(['status'=>'error', 'message'=>'Mensaje demasiado largo.'], 400);
        $saved = $this->ChatModel->insertMessage([
            'id_chats' => $chatId, 'id_employee' => $userId, 'message' => $stored, 'is_encrypted' => (int)$encrypted
        ]);
        $messageId = $saved ? $this->db->insert_id() : null;
        $participants = $this->db->select('id_employee')->where('id_chats', $chatId)->get('chat_participants')->result();
        return $this->json(['status'=>$saved ? 'ok' : 'error', 'id_messages'=>$messageId,
            'message'=>$message, 'user_id'=>$userId, 'user_name'=>$this->session->userdata('first_name'),
            'is_encrypted'=>(int)$encrypted, 'profile_picture'=>$this->session->userdata('profile_picture'),
            'participants'=>array_map(function($p){return (int)$p->id_employee;}, $participants)], $saved ? 200 : 500);
    }


    // GET MESSAGES
    public function get_messages($chat_id) {
        if (!$this->member($chat_id)) return $this->json(["status"=>"error"], 403);

        $this->load->model("ChatModel");

        $messages = $this->ChatModel->getMessages($chat_id);

        echo json_encode($messages);
    }

    public function get_user_chats($user_id) {
        if (!$this->session->userdata("id") || (int)$user_id !== (int)$this->session->userdata("id")) return $this->json(["status"=>"error"], 403);

        $this->load->model("ChatModel");

        $chats = $this->ChatModel->getUserChats($user_id);

        echo json_encode($chats);
    }

}
