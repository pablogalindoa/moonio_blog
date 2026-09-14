<?php
class MundialesModel extends CI_Model {

    public function getAll() {
        return $this->db->get('mundiales')->result();
    }

    public function insert($data) {
        return $this->db->insert('mundiales', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id_mundiales', $id)->update('mundiales', $data);
    }

    public function getCountries() {
        $this->db->where('enable', 1);
        $query = $this->db->get('countries');
        return $query->result();
    }

    
}
