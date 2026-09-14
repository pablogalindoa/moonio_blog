<?php
class WorldCupModel extends CI_Model {

    public function createWorldCup($data) {
        return $this->db->insert('mundiales', $data);
    }

    public function getEnabledWorldCups() {
        return $this->db->where('enable', 1)
                        ->order_by('year', 'DESC')
                        ->get('mundiales')
                        ->result();
    }

    public function getWorldCup($id) {
        return $this->db->where('id_mundiales', $id)
                        ->get('mundiales')
                        ->row();
    }
}
