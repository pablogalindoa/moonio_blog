<?php
class HomeModel extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    
}
public function getAllBlogs() {
    $this->db->select('blogs.*, employee.first_name, employee.last_name'); // Fetch author name
    $this->db->from('blogs');
    $this->db->join('employee', 'blogs.author_id = employee.id'); // Join on author_id
    $this->db->order_by('blogs.created_at', 'DESC'); // Order by the created_at field in descending order (newest first)
    $query = $this->db->get();
    return $query->result();
}


public function get_blog_by_id($id) {
    $query = $this->db->get_where('blogs', ['id' => $id]);
    return $query->row();
}

public function create_blog($imagePath) {
    $data = [
        'title' => $this->input->post('title'),
        'category' => $this->input->post('category'),
        'content' => $this->input->post('content'),
        'image' => $imagePath,
        'author_id' => $this->session->userdata('user_id'),
    ];
    return $this->db->insert('blogs', $data);
}

public function update_blog($id) {
    $data = [
        'title' => $this->input->post('title'),
        'content' => $this->input->post('content'),
    ];
    return $this->db->update('blogs', $data, ['id' => $id]);
}

public function delete_blog($id) {
    return $this->db->delete('blogs', ['id' => $id]);
}

public function insertBlog($data) {
  return $this->db->insert('blogs', $data);
}

public function getBlogs($limit, $offset) {

    $query = $this->db->query("
            SELECT bg.*, emp.first_name, emp.last_name
            FROM blogs AS bg
            LEFT JOIN employee AS emp 
            ON bg.author_id = emp.id
            WHERE bg.enable = 1
            ORDER BY bg.created_at DESC
            LIMIT $limit
            OFFSET $offset
        ");

    // $this->db->select('bs.*, em.first_name'); // Select blogs with author's name
    // $this->db->from('blogs AS bs');
    // $this->db->join('employee AS em', 'bs.author_id = em.id', 'left');
    // $this->db->where('bs.enable', 1); // Only enabled blogs
    // $this->db->order_by('bs.created_at', 'DESC'); // Order newest first
    // $this->db->limit($limit, $offset);
    // $query = $this->db->get();
    return $query->result();
}

public function countBlogs() {
    $this->db->where('enable', 1);
    $this->db->from('blogs');
    return $this->db->count_all_results();
}

public function searchBlogs($query) {
    $this->db->select('*');
    $this->db->from('blogs'); // Use your actual blog table name
    $this->db->like('title', $query);
    $this->db->or_like('content', $query);
    $this->db->where('enable', 1); // Optional: if you only want published posts
    $query = $this->db->get();
    return $query->result();
}

public function individualBlog($id) {
    $this->db->select('bs.id, bs.title, bs.image, bs.content, bs.author_id, bs.created_at, em.first_name');
    $this->db->from('blogs AS bs');
    $this->db->join('employee AS em', 'bs.author_id = em.id', 'left');
    $this->db->where('bs.id', $id);
    $this->db->where('bs.enable', 1);
    
    $query = $this->db->get();
    return $query->row(); // Return a single row as an object
}

public function myPosts($id) {
    $this->db->select('bs.id, bs.title, bs.image, bs.content, bs.author_id, bs.created_at, em.first_name, em.id');
    $this->db->from('employee AS em');
    $this->db->join('blogs AS bs', 'em.id = bs.author_id', 'left');
    $this->db->where('em.id', $id);
    $this->db->where('em.enable', 1);
    $this->db->where('bs.enable', 1);
    
    $query = $this->db->get();
    return $query->result(); // Return a single row as an object
}

public function getCategories() {
    $query = $this->db->get('categories');
    return $query->result();
}

public function search($query = null, $category = null, $start_date = null, $end_date = null) {
    $this->db->select('bs.*, em.first_name, c.name as category_name');
    $this->db->from('blogs as bs');
    $this->db->join('employee as em', 'bs.author_id = em.id', 'left');
    $this->db->join('categories as c', 'bs.category = c.id_categories', 'left');
    $this->db->where('bs.enable', 1);

    if (!empty($query)) {
        $this->db->group_start()
            ->like('bs.title', $query)
            ->or_like('bs.content', $query)
            ->group_end();
    }

    if (!empty($category)) {
        $this->db->where('bs.category', $category);
    }

    if (!empty($start_date)) {
        $this->db->where('bs.created_at >=', $start_date);
    }

    if (!empty($end_date)) {
        $this->db->where('bs.created_at <=', $end_date);
    }

    $this->db->order_by('bs.created_at', 'DESC');
    return $this->db->get()->result();
}

public function getBlogsByIds($ids) {
    if (empty($ids)) return [];

    $this->db->select('blogs.*, employees.first_name');
    $this->db->from('blogs');
    $this->db->join('employees', 'employees.id = blogs.author_id');
    $this->db->where_in('blogs.id', $ids);
    $this->db->order_by('blogs.created_at', 'DESC');

    $query = $this->db->get();
    return $query->result();
}

public function getEnabledWorldCups() {
    $this->db->select('*');
    $this->db->from('mundiales');
    $this->db->where('enable', 1);       // Only enabled world cups
    $this->db->order_by('year', 'ASC');  // Order from oldest to newest
    return $this->db->get()->result();
}


}

