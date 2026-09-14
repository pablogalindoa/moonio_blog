<?php
class ChampionsModel extends CI_Model {

    public function getChampions() {
      return $this->db->order_by('year', 'ASC')->get('worldcup_champions')->result_array();
    }

    public function getMostTitles() {
        return $this->db->select('champion, COUNT(*) as titles')
                        ->group_by('champion')
                        ->order_by('titles', 'DESC')
                        ->limit(1)
                        ->get('worldcup_champions')
                        ->row_array();
    }

    }
