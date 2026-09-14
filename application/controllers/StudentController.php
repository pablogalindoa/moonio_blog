<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class StudentController extends CI_Controller {

    public function index() 
    {
        $this->load->model('StudentModel', 'stud');
    //    $student = $this->StudentModel->studentData();
    //    $student = new StudentModel;
    //    $student= $student->studentData();
    $student = $this->stud->studentData();
    //$student_class = $this->stud->studentClass();
        echo "Student Name: ". $student;
    }

    public function show($id) {

        $this->load->model("StudentModel","stud");
        $student = $this->stud->studentShow($id);
        echo $student;
    }
}