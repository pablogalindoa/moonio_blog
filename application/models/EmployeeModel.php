<?php

class EmployeeModel extends CI_Model {

    //public $employee_name = '';

    public function insertEmployee($data)
    {
        $result = $this->db->insert('employee', $data);

        if ($result) {
            $employeeId = $this->db->insert_id();

            $this->addPoints($employeeId, 2);
        }

        return $result;
    }

    public function getEmployee() {
        $this->db->where('enable', 1);  // only active employees
        $query = $this->db->get('employee');
        return $query->result();
    }


    public function editEmployee($id) {

        $query = $this->db->get_where('employee', array('id'=> $id));
        return $query->row();
    }

    public function updateEmployee($data, $id) {
        return $this->db->update('employee', $data, array('id' => $id));
        
    }

    public function deleteEmployee($id) {
        //return $this->db->delete('employee', array('id' => $id)); 
        $this->db->where('id', $id);
        return $this->db->update('employee', array('enable' => 0));
    }

    public function removeEmployee( $id ) {
        $this->db->where('id', $id);
        return $this->db->update('employee', array('enable' => 0));
    }

    public function checkEmployeeLogin($email) {
        $query = $this->db->get_where('employee', ['email'=> $email]);
        return $query->row();
    }

    // In your model (recommended)
    public function getEmployeeByEmail($email)
    {
        return $this->db
            ->where('email', $email)
            ->where('enable', 1)
            ->get('employee')
            ->row();
    }

    public function getEmployeeById($id) {
        return $this->db->get_where('employee', ['id' => $id])->row();
    }

    public function addPoints($id, $type) {
        $rewardType = $this->db->get_where(
            'reward_types',
            ['id_reward_type' => $type]
        )->row();

        $currentPoints = $this->db->get_where(
            'employee',
            ['id' => $id]
        )->row();

        $points = $rewardType->points + $currentPoints->points;

        $dataUpdate = array(
            'points' => $points
        );

        $this->db->update('employee', $dataUpdate, array('id' => $id));

        $dataInsert = array(
                'id_employee' => $id,
                'id_reward_type' => $rewardType->id_reward_type,
                'points' => $rewardType->points
            ); 

        return $this->db->insert('rewards', $dataInsert);
    }

    public function getAllEmployeeInfo($id)
    {
        $employee = $this->db->get_where(
            'employee',
            ['id' => $id]
        )->row();

        $totalMessages = $this->db
            ->where('id_employee', $id)
            ->count_all_results('messages');

        $employee->total_messages = $totalMessages;

        // División
        if ($employee->points >= 2000) {
            $employee->division = 'Campeón Mundial 🌎🏆';
        }
        elseif ($employee->points >= 1000) {
            $employee->division = 'Liga Élite 🔥';
        }
        elseif ($employee->points >= 600) {
            $employee->division = 'Liga Diamante 💎';
        }
        elseif ($employee->points >= 300) {
            $employee->division = 'Liga Oro 🥇';
        }
        elseif ($employee->points >= 150) {
            $employee->division = 'Liga Plata ⚪';
        }
        elseif ($employee->points >= 50) {
            $employee->division = 'Liga Bronce 🟤';
        }
        else {
            $employee->division = 'Liga Amateur ⚽';
        }

        // Trofeos

        $trophies = $this->db->get('trophies')->result();

        $employee->total_trophies = 0;

        foreach ($trophies as $trophy)
        {
            $unlocked = false;

            if (
                $trophy->requirement_type == 'points'
                && $employee->points >= $trophy->requirement_value
            ) {
                $unlocked = true;
            }

            if (
                $trophy->requirement_type == 'messages'
                && $employee->total_messages >= $trophy->requirement_value
            ) {
                $unlocked = true;
            }

            if ($unlocked) {
                $employee->total_trophies++;
            }
        }

        return $employee;
    }

    public function getTrophies($id)
    {
        $employee = $this->getAllEmployeeInfo($id);

        $trophies = $this->db
            ->get('trophies')
            ->result();

        foreach ($trophies as $trophy)
        {
            $trophy->unlocked = false;

            if (
                $trophy->requirement_type == 'messages'
                && $employee->total_messages >= $trophy->requirement_value
            ) {
                $trophy->unlocked = true;
            }

            if (
                $trophy->requirement_type == 'points'
                && $employee->points >= $trophy->requirement_value
            ) {
                $trophy->unlocked = true;
            }
        }

        return $trophies;
    }
    
}