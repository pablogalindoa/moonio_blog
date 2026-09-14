<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BlogController extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('BlogModel');
    $this->load->helper(array('form', 'url'));
}

// public function index() 
//     {
//         if (!$this->session->userdata('id')) {
//             redirect(base_url('index.php/blogs'));
//         }
        
//         $this->load->view('templates/header');
//         $this->load->model('EmployeeModel', 'emp');
//         $this->load->model('BlogModel', 'blg');
//         //$data['employee'] = $this->emp->getEmployee();
//         // $employee = $this->emp->getEmployee();
//         $blog = $this->BlogModel->getAllBlogs();
//         $this->load->view('blogs/blog', ['blogs'=> $blog]);
//         $this->load->view('templates/footer');
        
//     }

public function index() {
    if (!$this->session->userdata('id')) {
        redirect(base_url('index.php/login'));
    }

    $this->load->library('pagination');
    $this->load->model('BlogModel');
    $this->load->model('EmployeeModel', 'emp');

    $employee = $this->emp->getEmployee();


    $sort = $this->input->get('sort'); 
    $filters = ['sort' => $sort];

    // Pagination variables
    $limit = 8;
    $page = $this->input->get('page', TRUE);
    $page = ($page && is_numeric($page)) ? (int)$page : 1;
    $offset = ($page - 1) * $limit;

    // Total blogs
    $total_rows = $this->BlogModel->countBlogs();

    // Pagination configuration
    $config['base_url'] = base_url('index.php/blog');
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $limit;
    $config['use_page_numbers'] = TRUE;
    $config['num_links'] = 2;

    // Bootstrap styling
    $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul>';
    $config['prev_link'] = '&laquo; Prev';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = 'Next &raquo;';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['attributes'] = ['class' => 'page-link'];

    $this->pagination->initialize($config);

    // Get blogs with sorting
    $blogs = $this->BlogModel->getBlogsFiltered($limit, $offset, $filters);

    // Pass data to view
    $this->load->view('templates/header_blog');
    $this->load->view('blogs/blog', [
        'blogs' => $blogs,
        'pagination' => $this->pagination->create_links(),
        'employee' => $employee,
        'filters' => $filters
    ]);
    $this->load->view('templates/footer');
}


    public function show($id) {
        $data['blog'] = $this->BlogModel->get_blog_by_id($id);
        $this->load->view('blog/single', $data);
    }

    public function create() {
        if (!$this->session->userdata('id')) {
            redirect(base_url('index.php/login'));
        }
    
        $this->load->model('BlogModel');
        $data['categories'] = $this->BlogModel->getCategories(); 
        $data['mundiales'] = $this->BlogModel->getMundiales(); 
    
        $this->load->view('templates/header');
        $this->load->view('blogs/blogcreate', $data);
        $this->load->view('templates/footer');
    }
    

    public function store() 
    {
        $config['upload_path']   = FCPATH . 'uploads/';  // Absolute server path
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp|mp4|mov|avi|mkv';
        $config['max_size']      = 500000; // 500MB (for videos)
        $config['encrypt_name']  = TRUE;   // recommended

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {

            $error['error'] = $this->upload->display_errors();

            // reload required dropdown data
            $error['mundiales'] = $this->BlogModel->getWorldCupYears();
            $error['categories'] = $this->BlogModel->getCategories();

            return $this->load->view('blogs/blogcreate', $error);
        }

        // Upload success
        $file = $this->upload->data();
        $file_name = $file['file_name'];

        // STORE ONLY THE FILE NAME — NOT "uploads/..."
        // This makes it easier to move files later
        $blogData = [
            'id_mundiales'        => $this->input->post('mundial'),
            'id_employee'         => $this->session->userdata('id'),
            'title'               => $this->input->post('title'),
            'id_blog_categories'  => $this->input->post('category'),
            'image'               => 'uploads/' . $file_name,   // prepend uploads/ 
            'content'             => $this->input->post('content')
        ];

        $this->BlogModel->requestBlog($blogData);

        $this->session->set_flashdata('status', 'Publicación está bajo revisión! :D');
        redirect(base_url('index.php/blog'));
    }
  
    
    public function blogRequest(){

        $data['requests'] = $this->BlogModel->getRequests();
        //$requests = $requests[0];
        //die(var_dump($data['requests'][0]));
        $this->load->view('templates/header');
        $this->load->view('blogs/blog_request', $data);
        $this->load->view('templates/footer');
    }

    public function handleRequest()
    {
        $id = $this->input->post('request_id');
        $action = $this->input->post('action');

        if ($action === "accept") {
            if ($this->BlogModel->publishFromRequest($id)) {
                $this->session->set_flashdata('message', '✔ La solicitud fue aceptada y el blog ha sido publicado.');
            } else {
                $this->session->set_flashdata('message', '❌ No se pudo aceptar la solicitud.');
            }
        }

        if ($action === "delete") {
            if ($this->BlogModel->deleteRequest($id)) {
                $this->session->set_flashdata('message', '🗑 La solicitud fue eliminada correctamente.');
            } else {
                $this->session->set_flashdata('message', '❌ No se pudo eliminar la solicitud.');
            }
        }

        redirect(base_url('index.php/home')); 
    }

    public function deleteRequest($id) {
        $data['blog_info'] = $this->BlogModel->getBlogInfo($id);
        $data['request'] = $this->BlogModel->newRequest($data, 'delete');
        redirect(base_url('index.php/home')); 
    }

    public function edit($id) {
        $data['blog'] = $this->BlogModel->get_blog_by_id($id);
        $this->load->view('blog/edit', $data);
    }

    public function update($id) {
        $this->BlogModel->update_blog($id);
        redirect('blog/' . $id); // Redirect to the updated blog post
    }

    public function delete($id) {
        $this->BlogModel->delete_blog($id);
        redirect('blog'); // Redirect to the blog home page
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
        //die(var_dump($blogs));
        $this->load->view('blogs/blog_individual', ['blogs'=>$blogs]);
        $this->load->view('templates/footer');

    }

    public function blogIndividualAdmin($id){

        $this->load->model('BlogModel', "bm");
        $this->load->model('CommentModel');
        $this->load->view('templates/header_user');
        $blogs = $this->bm->individualBlogAdmin($id);
        $comments = $this->CommentModel->getCommentsByBlog($id);
        //die(var_dump($blogs));
        $this->load->view('blogs/blog_individual_admin', ['blogs'=>$blogs, 'comments' => $comments]);
        $this->load->view('templates/footer');

    }

    public function blogAdmin(){
        $id = $this->uri->segment(3);
        $this->load->model('BlogModel', "bm");
        $this->load->view('templates/header_blog_admin');
        $blogs = $this->bm->getAdminBlogs($id);
        //die(var_dump($blogs));
        $this->load->view('blogs/blog_admin', ['blogs'=>$blogs]);
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
            $blogUrl = base_url('index.php/blog/blog_individual_admin/' . $blog->id_blogs_admin);
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

    public function toggle_like_admin()
    {
        $id_blogs_admin = $this->input->post('id_blogs_admin');
        $id_employee = $this->session->userdata('id');

        // Validate both values
        if (!$id_blogs_admin || !$id_employee) {
            echo json_encode([
                'status' => 'error',    
                'message' => 'Missing blog ID or user session.'
            ]);
            return;
        }

        $liked = $this->BlogModel->toggle_like_admin($id_blogs_admin, $id_employee);
        $count = $this->BlogModel->get_like_count_admin($id_blogs_admin);

        echo json_encode([
            'status' => 'success',
            'liked' => $liked,
            'count' => $count
        ]);
    }

    public function addComment() {
        $blog_id = $this->input->post('blog_id');
        $user_id = $this->session->userdata('id'); // logged-in user
        $comment_text = trim($this->input->post('comment'));

        if (empty($comment_text)) {
            $this->session->set_flashdata('error', 'Comment cannot be empty.');
            redirect(base_url('index.php/blog/blog_individual_admin/' . $blog_id));
        }

        $this->load->model('CommentModel');

        $comment_data = [
            'blog_id'   => $blog_id,
            'user_id'   => $user_id,
            'comment'   => $comment_text,
            'created_at'=> date('Y-m-d H:i:s') // timestamp
        ];

        $this->CommentModel->addComment($comment_data);

        $this->session->set_flashdata('success', 'Comment added successfully!');
        redirect(base_url('index.php/blog/blog_individual_admin/' . $blog_id));
    }

    public function filter()
    {
        $this->load->model('BlogModel');

        $category = $this->input->get('category');
        $year     = $this->input->get('year');
        $country  = $this->input->get('country');
        $user     = $this->input->get('user');

        $blogs = $this->BlogModel->filterBlogs($category, $year, $country, $user);

        // Load filter options
        $categories = $this->BlogModel->getCategories();
        $years = $this->BlogModel->getWorldCupYears();
        $countries = $this->BlogModel->getCountries();
        $users = $this->BlogModel->getUsers();

        $this->load->view('blog_admin', [
            'blogs' => $blogs,
            'categories' => $categories,
            'years' => $years,
            'countries' => $countries,
            'users' => $users
        ]);
    }

}
