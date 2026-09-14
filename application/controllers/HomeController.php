<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HomeController extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('HomeModel', 'hm');
    $this->load->model('EmployeeModel', 'emp');
    $this->load->helper(array('form', 'url'));
}

    public function index() {

        $this->load->library('pagination');
        $this->load->model('HomeModel');

        // Load only enabled World Cups
        $worldCups = $this->HomeModel->getEnabledWorldCups();

        $employee = $this->emp->getEmployee();

        $this->load->view('templates/header_home');
        $this->load->view('home/chats_fifa_v', ['worldCups' => $worldCups]);
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
    
    public function profile() {

        $this->load->model('BlogModel');
        $this->load->view('templates/header_user');
        $this->load->view('blogs/user_profile');
        $this->load->view('templates/footer');
    }

    public function blogIndividual($id){

        $this->load->model('BlogModel', "bm");
        $this->load->view('templates/header_user');
        $blogs = $this->bm->individualBlog($id);
        $this->load->view('blogs/blog_individual', ['blogs'=>$blogs]);
        $this->load->view('templates/footer');

    }

    public function blogMyPosts($id){

        $this->load->model('BlogModel', "bm");
        $this->load->view('templates/header_user');
        $blogs = $this->bm->myPosts($id);
        $this->load->view('blogs/blog_my_posts', ['blogs'=>$blogs]);
        $this->load->view('templates/footer');

    }

    public function liveSearch() {
        $query = $this->input->get('query');
        $results = $this->BlogModel->searchBlogs($query);
        foreach ($results as $blog) {

            if($blog->enable == 1){
                // Build blog post URL (e.g., blog/view/{id})
            $blogUrl = base_url('index.php/blog/blog_individual/' . $blog->id);
            $imageUrl = base_url($blog->image);
    
            echo "<div class='mb-4 p-3 border rounded shadow-sm bg-white'>";
            
            // Clickable image
            echo "<a href='$blogUrl'>";
            echo "<img src='$imageUrl' alt='Blog image' width='300' height='220' class='mx-auto d-block border border-gray-300 shadow-md mb-2 rounded'>";
            echo "</a>";
    
            // Title (also clickable)
            echo "<h5 class='text-center mt-2'>";
            echo "<a href='$blogUrl' class='text-decoration-none text-dark'>";
            echo htmlspecialchars($blog->title);
            echo "</a>";
            echo "</h5>";
    
            echo "</div>";
            }
            
        }
    }
    public function blogSearch() {
        $this->load->model('BlogModel');
    
        $query       = $this->input->get('query');
        $category    = $this->input->get('category');
        $start_date  = $this->input->get('start_date');
        $end_date    = $this->input->get('end_date');
    
        $blogs = $this->BlogModel->search($query, $category, $start_date, $end_date);
        $categories = $this->BlogModel->getCategories();
    
        $this->load->view('templates/header_blog');
        $this->load->view('blogs/blog_search', ['blogs' => $blogs, 'categories' => $categories]);
        $this->load->view('templates/footer');
    }

    public function rewards()
    {
        $id = $this->session->userdata('id');
        $data['employee'] = $this->emp->getAllEmployeeInfo($id);
        $data['trophies'] = $this->emp->getTrophies($id);

        $this->load->view('templates/header_home');
        $this->load->view('home/rewards_fifa_v', $data);
        $this->load->view('templates/footer');
    }

    public function taskManager()
    {
        $id = $this->session->userdata('id');
        $data['employee'] = $this->emp->getAllEmployeeInfo($id);
        $data['trophies'] = $this->emp->getTrophies($id);
        
        $this->load->view('templates/header_home');
        $this->load->view('home/task_manager_fifa_v', $data);
        $this->load->view('templates/footer');
    }
    
}
