<?php

class StudentModel extends CI_Model {

    //public $student_name = '';

    public function studentData() {
        $stud_class = $this->studentClass();
        return $student_name = 'Pablo' . " His last class was: ". $stud_class;
    }

    private function studentClass() {
        return $student_class = "Software Development III";
    }

    public function studentShow($id) {
        if($id <= 10){
            echo "Tas viejo";

        } else {
            echo "Tas Joven";
        }
    }

    public function demo() {

        return $title = "Hello I am Pablo, the new Software Developer!";
    }

}