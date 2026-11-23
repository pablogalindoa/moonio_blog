<?php

class TaskModel extends CI_Model {

    public function getTasksByStatus($status)
    {
        return $this->db
            ->select("tasks.*, chats.name AS group_name, CONCAT(employee.first_name, ' ', employee.last_name) AS assignee_name")
            ->join('chats', 'chats.id_chats=tasks.id_chats', 'left')
            ->join('employee', 'employee.id=tasks.assigned_to', 'left')
            ->where('status', $status)
            ->where('tasks.enable', 1)
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