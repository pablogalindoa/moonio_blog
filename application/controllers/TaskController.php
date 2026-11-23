<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TaskController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('id')) { redirect('login'); }
        $this->load->model('HomeModel', 'hm');
        $this->load->model('EmployeeModel', 'emp');
        $this->load->model('TaskModel', 'task');
        $this->load->helper(array('form', 'url'));
    }

    public function index()
    {
        $data['bench_tasks'] =
            $this->task->getTasksByStatus('bench');

        $data['training_tasks'] =
            $this->task->getTasksByStatus('training');

        $data['goal_tasks'] =
            $this->task->getTasksByStatus('goal');

        $this->load->view('templates/header_home');
        $this->load->view(
            'home/task_manager2_v',
            $data
        );

        $this->load->view('templates/footer');
    }


    public function checkSession() {
        if ($this->session->userdata('id')) { // Use 'id', not 'user_id'
            echo "User ID: " . $this->session->userdata('id');
            echo "<br>Username: " . $this->session->userdata('first_name');
        } else {
            echo "No user logged in.";
        }
    }
    
    private function validGroupAssignment($chatId, $assignee) {
        return $chatId && $assignee
            && $this->db->where('id_chats', $chatId)->where('type', 'grupo')->count_all_results('chats')
            && $this->db->where('id_chats', $chatId)->where('id_employee', (int)$this->session->userdata('id'))->count_all_results('chat_participants')
            && $this->db->where('id_chats', $chatId)->where('id_employee', $assignee)->count_all_results('chat_participants');
    }

    public function store()
    {
        $chatId = (int)$this->input->post('id_chats');
        if (!$this->validGroupAssignment($chatId, (int)$this->input->post('assigned_to'))) { show_error('Selecciona un grupo tuyo y un integrante del grupo.', 400); return; }
        $data = [

            'id_chats' => $chatId,
            'title' =>
                $this->input->post('title'),

            'description' =>
                $this->input->post('description'),

            'status' =>
                $this->input->post('status'),
            
            'priority' =>
                $this->input->post('priority'),

            'assigned_to' =>
                $this->input->post('assigned_to'),

            'created_by' =>
                $this->session->userdata('id')
        ];

        $this->task->insertTask($data);

        redirect('task_manager');
    }    

    public function create()
    {
        $data['groups'] = $this->db->select('c.id_chats,c.name')->from('chats c')->join('chat_participants cp','cp.id_chats=c.id_chats')->where('cp.id_employee',(int)$this->session->userdata('id'))->where('c.type','grupo')->get()->result();
        $data['group_members'] = [];
        foreach ($data['groups'] as $group) {
            $data['group_members'][$group->id_chats] = $this->db->select('e.id,e.first_name,e.last_name')->from('employee e')->join('chat_participants cp','cp.id_employee=e.id')->where('cp.id_chats',$group->id_chats)->get()->result();
        }
        $data['employees'] = $this->db
            ->order_by('first_name', 'ASC')
            ->get('employee')
            ->result();

        $this->load->view('templates/header_home');
        $this->load->view('home/task_create_v', $data);
        $this->load->view('templates/footer');
    }

    public function advance($id)
    {
        $task = $this->task->getTaskById($id);

        if(!$task)
        {
            redirect('task_manager');
        }

        $newStatus = $task->status;

        if($task->status == 'bench')
        {
            $newStatus = 'training';
        }
        elseif($task->status == 'training')
        {
            $newStatus = 'goal';
        }

        $this->task->updateTask($id, [
            'status' => $newStatus
        ]);

        redirect('task_manager');
    }

    public function delete($id)
    {
        $this->task->deleteTask($id);

        redirect('task_manager');
    }

    public function edit($id)
    {
        $data['task'] = $this->task->getTaskById($id);

        $data['employees'] = $this->db
            ->get('employee')
            ->result();

        $this->load->view('templates/header_home');
        $this->load->view('home/task_edit_v', $data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $existing = $this->task->getTaskById($id);
        if (!$existing) { show_404(); return; }
        if ($existing->id_chats && !$this->validGroupAssignment((int)$existing->id_chats, (int)$this->input->post('assigned_to'))) { show_error('El usuario debe pertenecer al grupo de la tarea.', 400); return; }
        $data = [
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'status' => $this->input->post('status'),
            'priority' => $this->input->post('priority'),
            'assigned_to' => $this->input->post('assigned_to')
        ];

        $this->task->updateTask($id, $data);

        redirect('task_manager');
    }
}
