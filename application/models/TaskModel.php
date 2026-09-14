<?php

class TaskModel extends CI_Model {

    public function getTasksByStatus($status)
    {
        return $this->db
            ->where('status', $status)
            ->where('enable', 1)
            ->get('tasks')
            ->result();
    }

    public function insertTask($data)
    {
        return $this->db->insert('tasks', $data);
    }

    public function getTaskById($id)
    {
        return $this->db
            ->where('id_task', $id)
            ->where('enable', 1)
            ->get('tasks')
            ->row();
    }

    public function updateTask($id, $data)
    {
        return $this->db
            ->where('id_task', $id)
            ->update('tasks', $data);
    }

    public function deleteTask($id)
    {
        return $this->db
            ->where('id_task', $id)
            ->update('tasks', [
                'enable' => 0
            ]);
    }
    public function restoreTask($id)
    {
        return $this->db
            ->where('id_task', $id)
            ->update('tasks', [
                'enable' => 1
            ]);
    }
       
}