<?php

class LocationModel extends CI_Model {

    public function saveLocation($data)
    {
        return $this->db->insert('user_locations', $data);
    }

    public function getLastLocation($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->order_by('created_at', 'DESC')
            ->get('user_locations')
            ->row();
    }
}