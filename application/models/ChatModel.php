<?php
class ChatModel extends CI_Model {

    public function createChat($data) {
        $this->db->insert("chats", $data);
        return $this->db->insert_id();
    }

    public function addParticipant($data) {
        return $this->db->insert("chat_participants", $data);
    }

    public function getMessages($chat_id) {

        $this->db->select("
            messages.id_messages,
            messages.message,
            messages.created_at,
            messages.id_employee,
            employee.first_name,
            employee.last_name,
            employee.profile_picture
        ");

        $this->db->from("messages");
        $this->db->join("employee", "employee.id = messages.id_employee");

        $this->db->where("messages.id_chats", $chat_id);

        $this->db->order_by("messages.created_at", "ASC");

        return $this->db->get()->result();
    }


    public function getPrivateChats($user_id) {

        $this->db->select("
            chats.id_chats,
            chats.type,
            chats.name,
            employee.id as other_user_id,
            employee.first_name,
            employee.last_name,
            employee.profile_picture as profile_picture,
            MAX(messages.message) as last_message,
            MAX(messages.created_at) as last_time
        ");

        $this->db->from("chat_participants cp1");

        $this->db->join("chats", "chats.id_chats = cp1.id_chats");

        $this->db->join(
            "chat_participants cp2",
            "cp1.id_chats = cp2.id_chats 
            AND cp2.id_employee != " . (int)$user_id
        );

        $this->db->join("employee", "employee.id = cp2.id_employee");

        $this->db->join("messages", "messages.id_chats = chats.id_chats", "left");

        $this->db->where("cp1.id_employee", $user_id);

        $this->db->where("chats.type", "privado");

        $this->db->group_by("chats.id_chats");

        return $this->db->get()->result();
    }


    public function getGroupChats($user_id) {

        $this->db->select("
            chats.id_chats,
            chats.type,
            chats.name,
            chats.group_image as profile_picture,
            MAX(messages.message) as last_message,
            MAX(messages.created_at) as last_time
        ");

        $this->db->from("chat_participants");

        $this->db->join(
            "chats",
            "chats.id_chats = chat_participants.id_chats"
        );

        $this->db->join(
            "messages",
            "messages.id_chats = chats.id_chats",
            "left"
        );

        $this->db->where("chat_participants.id_employee", $user_id);

        $this->db->where("chats.type", "grupo");

        $this->db->group_by("chats.id_chats");

        return $this->db->get()->result();
    }

    public function getUserChats($user_id) {

        $privateChats = $this->getPrivateChats($user_id);

        $groupChats = $this->getGroupChats($user_id);

        $allChats = array_merge($privateChats, $groupChats);

        usort($allChats, function($a, $b) {
            return strtotime($b->last_time) - strtotime($a->last_time);
        });

        return $allChats;
    }

    public function insertMessage($data) {
        return $this->db->insert("messages", $data);
    }

}
