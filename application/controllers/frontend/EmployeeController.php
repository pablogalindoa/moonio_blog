<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class EmployeeController extends CI_Controller {

    public function index() 
    {
        if (!$this->session->userdata('id')) {
            redirect(base_url('index.php/login'));
        }
        
        $this->load->view('templates/header');
        $this->load->model('EmployeeModel', 'emp');
        //$data['employee'] = $this->emp->getEmployee();
        $employee = $this->emp->getEmployee();
        $this->load->view('frontend/employee', ['employee'=> $employee]);
        $this->load->view('templates/footer');

        $do = $this->uri->segment(2);
        
    }

    public function show($id) 
    {
        $this->load->model("EmployeeModel","emp");
        $employee = $this->emp->employeetShow($id);
        echo $employee;
    }

    public function create() 
    {   
        $this->load->model("EmployeeModel","emp");
        $data['employees'] = $this->emp->getEmployee();
        $this->load->view('templates/header');
        $this->load->view('frontend/create', $data);
        $this->load->view('templates/footer');
    }

    public function store() 
    {
        
        $config['upload_path'] = 'uploads/users/'; 
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; 
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('upload', $config);
        $this->load->model('EmployeeModel', 'emp');

        
        // FIRST NAME (letters + spaces, accents allowed)
        $this->form_validation->set_rules(
            'first_name',
            'First Name',
            'required|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/]',
            array('regex_match' => 'El nombre solo puede contener letras y espacios.')
        );

        // LAST NAME (letters + spaces)
        $this->form_validation->set_rules(
            'last_name',
            'Last Name',
            'required|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/]',
            array('regex_match' => 'El apellido solo puede contener letras y espacios.')
        );

        // BIRTH DATE (valid + age >= 12)
        $this->form_validation->set_rules(
            'birth_date',
            'Birth Date',
            'required|callback_valid_birth_date|callback_age_check'
        );

        // PHONE (only numbers)
        $this->form_validation->set_rules(
            'phone',
            'Phone Number',
            'required|regex_match[/^[0-9]+$/]',
            array('regex_match' => 'El número telefónico solo puede contener números sin espacios ni símbolos.')
        );

        // EMAIL (lowercase + valid + only one .com)
        $this->form_validation->set_rules(
            'email',
            'Email',
            'required|valid_email|callback_valid_single_com|regex_match[/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/]',
            array('regex_match' => 'El email debe estar en minúsculas y en un formato válido.')
        );

        // PASSWORD (unchanged)
        $this->form_validation->set_rules(
            'password',
            'Password',
            'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/]',
            array(
                'regex_match' => 'La contraseña necesita contar con al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.'
            )
        );
   
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('frontend/create');
            $this->load->view('templates/footer');
            return;
        }

        $referrer = $this->input->post('referrer_id') ?: NULL;
        if($referrer){
            $this->emp->addPoints($referrer, 3);
        }
        
        if (!$this->upload->do_upload('profile_picture')) {
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('frontend/create', $error);
            $this->session->set_flashdata('status','Failed to Insert Picture');
        } else {
            $data = $this->upload->data();
            $image_path = 'uploads/users/' . $data['file_name']; 
            
            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name'=> $this->input->post('last_name'),
                'birth_date'=> $this->input->post('birth_date'),
                'phone' => $this->input->post('phone'),
                'email'=> $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'profile_picture' => $image_path,
            ];
            
            if ($this->emp->insertEmployee($data)) {
                $this->session->set_flashdata('status','Data de usuario ha sido insertada satisfactoriamente!');
                redirect(base_url('index.php/login'));
            } else {
                echo $this->db->error()['message']; 
                exit;
            }
        }
    }

    public function age_check($birth_date)
    {
        $birth_timestamp = strtotime($birth_date);
        $age = (int)((time() - $birth_timestamp) / (365.25*24*60*60));

        if ($age < 12) {
            $this->form_validation->set_message('age_check', 'Debes tener al menos 12 años para registrarte.');
            return FALSE;
        }
        return TRUE;
    }


    public function edit($id){
        $this->load->view('templates/header');
        $this->load->model("EmployeeModel", "emp");
        $employee = $this->emp->editEmployee($id);
        $this->load->view('frontend/edit', ['employee'=> $employee]);
        $this->load->view('templates/footer');
        
    }

    public function update($id)
    {
        
        $config['upload_path']   = 'uploads/users/'; 
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048; 

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('upload', $config);
        $this->load->Model('EmployeeModel', 'emp');

        // FIRST NAME (letters + spaces, accents allowed)
        $this->form_validation->set_rules(
            'first_name',
            'First Name',
            'required|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/]',
            array('regex_match' => 'El nombre solo puede contener letras y espacios.')
        );

        // LAST NAME (letters + spaces)
        $this->form_validation->set_rules(
            'last_name',
            'Last Name',
            'required|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/]',
            array('regex_match' => 'El apellido solo puede contener letras y espacios.')
        );

        // BIRTH DATE (valid + age >= 12)
        $this->form_validation->set_rules(
            'birth_date',
            'Birth Date',
            'required|callback_valid_birth_date|callback_age_check'
        );

        // PHONE (only numbers)
        $this->form_validation->set_rules(
            'phone',
            'Phone Number',
            'required|regex_match[/^[0-9]+$/]',
            array('regex_match' => 'El número telefónico solo puede contener números sin espacios ni símbolos.')
        );

        // EMAIL (lowercase + valid + only one .com)
        $this->form_validation->set_rules(
            'email',
            'Email',
            'required|valid_email|callback_valid_single_com|regex_match[/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/]',
            array('regex_match' => 'El email debe estar en minúsculas y en un formato válido.')
        );

        // PASSWORD (unchanged)
        $this->form_validation->set_rules(
            'password',
            'Password',
            'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/]',
            array(
                'regex_match' => 'La contraseña necesita contar con al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.'
            )
        );


        if ($this->form_validation->run() == FALSE) {

            $employee = $this->emp->editEmployee($id);
            $this->load->view('templates/header');
            $this->load->view('frontend/edit', ['employee' => $employee]);
            $this->load->view('templates/footer');
            return;
        }

        $data = [
            'first_name' => $this->input->post('first_name'),
            'last_name'  => $this->input->post('last_name'),
            'birth_date' => $this->input->post('birth_date'),
            'phone'      => $this->input->post('phone'),
            'email'      => $this->input->post('email'),
            'password'   => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        ];

        if (!empty($_FILES['profile_picture']['name'])) {
            if (!$this->upload->do_upload('profile_picture')) {
                $error = array('error' => $this->upload->display_errors());
                $employee = $this->emp->editEmployee($id);
                $this->load->view('templates/header');
                $this->load->view('frontend/edit', ['employee' => $employee, 'error' => $error]);
                $this->load->view('templates/footer');
                return;
            } else {
                $file_data = $this->upload->data();
                $data['profile_picture'] = 'uploads/users/' . $file_data['file_name'];
            }
        }

        if ($this->emp->updateEmployee($data, $id)) {
            $this->session->set_flashdata('status','Datos de usuario han sido insertados satisfactoriamente!');
            redirect(base_url('index.php/employee'));
        } else {
            echo $this->db->error()['message'];
            exit;
        }
    }

        
    public function delete($id){ 
        $this->load->model("EmployeeModel", "emp");

        // 1. Get employee first
        $employee = $this->emp->getEmployeeById($id);

        // 2. Load the view with employee data
        $this->load->view('templates/header');
        $this->load->view('frontend/delete', ['employee'=> $employee]);
        $this->load->view('templates/footer');
    }


    public function getEmployeeById($id) {
        return $this->db->get_where('employee', ['id' => $id])->row();
    }


    public function remove($id){
        $this->load->model("EmployeeModel", "emp");
        $this->emp->removeEmployee($id);
        redirect(base_url('/index.php/employee'));
    }

    public function login() {
        $this->load->view('templates/header');
        $this->load->view('frontend/login');
        $this->load->view('templates/footer');
    }

    public function loginAuth() {
        $this->load->model("EmployeeModel", "emp");
        
        $email = $this->input->post('email');
        $password = $this->input->post('password');
    
        $employee = $this->emp->getEmployeeByEmail($email); 
    
        if ($employee && password_verify($this->input->post('password'), $employee->password)) {

            $this->session->set_userdata('id', $employee->id);
            $this->session->set_userdata('is_admin', $employee->is_admin);
            $this->session->set_userdata('first_name', $employee->first_name);
            $this->session->set_userdata('last_name', $employee->last_name);
            $this->session->set_userdata('email', $employee->email);
            $this->session->set_userdata('phone', $employee->phone);
            $this->session->set_userdata('profile_picture', $employee->profile_picture);
            $this->emp->addPoints($employee->id, 1);
            redirect('home');
            $this->session->set_flashdata('status', $employee->first_name);

        } else {

            $this->session->set_flashdata('status', 'Usuario o Contraseña Inválido');
            redirect('login');

        }
    
        exit;
    }
    
    public function valid_birth_date($date)
    {
        // Format: yyyy-mm-dd
        $parts = explode('-', $date);
        if (count($parts) !== 3) {
            $this->form_validation->set_message('valid_birth_date', 'El formato de fecha es inválido.');
            return FALSE;
        }
    
        $year = (int)$parts[0];
        $month = (int)$parts[1];
        $day = (int)$parts[2];
    
        if (!checkdate($month, $day, $year)) {
            $this->form_validation->set_message('valid_birth_date', 'La fecha de nacimiento no es válida.');
            return FALSE;
        }
    
        $inputDate = strtotime($date);
        $today = strtotime(date('Y-m-d'));
    
        if ($inputDate > $today) {
            $this->form_validation->set_message('valid_birth_date', 'The Birth Date cannot be in the future.');
            return FALSE;
        }
    
        return TRUE;
    }

    public function valid_single_com($email)
    {
        if (substr_count($email, '.com') > 1) {
            $this->form_validation->set_message('valid_single_com', 'El correo no puede contener más de un ".com".');
            return FALSE;
        }

        if ($email !== strtolower($email)) {
            $this->form_validation->set_message('valid_single_com', 'El correo debe estar en minúsculas.');
            return FALSE;
        }

        return TRUE;
    }

    
    
    public function logout() {
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('first_name');
        $this->session->set_flashdata('status', 'You have been logged out.');
        redirect('login');
    }
    
}