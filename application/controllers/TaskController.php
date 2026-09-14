<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TaskController extends CI_Controller {
    public function __construct() {
        parent::__construct();
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
            'home/task_manager_fifa_v',
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
    
    public function store()
    {
        $data = [

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
        $data['employees'] = $this->db
            ->order_by('first_name', 'ASC')
            ->get('employee')
            ->result();

        $this->load->view('templates/header_home');
        $this->load->view('home/task_create_fifa_v', $data);
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
        $this->load->view('home/task_edit_fifa_v', $data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
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
