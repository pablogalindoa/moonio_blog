<?php
class BlogModel extends CI_Model {

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

public function requestBlog($data) {
    IF($data) extract($data);

    // IMPORTANT: escape & quote string values for SQL
    $title_sql   = $this->db->escape($title);
    $image_sql   = $this->db->escape($image);
    $content_sql = $this->db->escape($content);

    $query = "INSERT INTO blogs_admin 
        (id_mundiales, id_employee, title, id_blog_categories, image, content, created_at, likes, enable)
        VALUES
        (   
            $id_mundiales,
            $id_employee,
            $title_sql, 
            $id_blog_categories, 
            $image_sql, 
            $content_sql,
            NOW(), 
            0, 
            1
        );
    ";

    $dataInsert = array(
        'id_employee' => $data['id_employee'],
        'id_mundiales' => $data['id_mundiales'],
        'id_blog_categories' => $data['id_blog_categories'],
        'value' => $query,
        'image' => $data['image'],
        'content' => $data['content'],
        'id_request_types' => 1,
        'enable' => 1
    );   

    return $this->db->insert('requests', $dataInsert);
}

public function getBlogInfo($id) {

    $query = 
        $this->db->query(" SELECT ba.*, e.*, m.*, bc.*
            FROM blogs_admin AS ba
            LEFT JOIN employee AS e
                ON ba.id_employee = e.id
            LEFT JOIN mundiales AS m
                ON ba.id_mundiales = m.id_mundiales
            LEFT JOIN blog_categories AS bc
                ON ba.id_blog_categories = bc.id_blog_categories
            WHERE ba.enable = 1 AND ba.id_blogs_admin = $id
        ;");  

    $row = $query->row();

    if (!$row) {
        return false; 
    } 
    
    return $this->fixImagePath($row);

}
public function newRequest($data, $type) {
    $data = $data['blog_info'];
    switch($type) {
        case 'delete':

            $id = $data->id_blogs_admin;

            $query = "  UPDATE blogs_admin SET enable = 0
                        WHERE id_blogs_admin = $id;
                        ";

            $dataInsert = array(
                'id_employee' => $data->id_employee,
                'id_mundiales' => $data->id_mundiales,
                'id_blog_categories' => $id,
                'value' => $query,
                'image' => $data->image,
                'content' => $data->content,
                'id_request_types' => 3,
                'enable' => 1
            );   
            break;

    }
    return $this->db->insert('requests', $dataInsert);
}

public function getRequests() {

    $query = 
        $this->db->query(" SELECT 
            r.*, e.*, m.*, bc.*, rt.*
            FROM requests AS r
            LEFT JOIN employee AS e
                ON r.id_employee = e.id
            LEFT JOIN mundiales AS m
                ON r.id_mundiales = m.id_mundiales
            LEFT JOIN blog_categories AS bc
                ON r.id_blog_categories = bc.id_blog_categories
            LEFT JOIN request_types AS rt
                ON r.id_request_types = rt.id_request_types
            WHERE r.enable = 1
    ;");
    $rows = $query->result();
    foreach ($rows as &$row) {
        $this->fixImagePath($row);
    }
    return $rows;

}

public function publishFromRequest($id)
{
    $query = $this->db->query("
        SELECT value
        FROM requests
        WHERE id_requests = ?
          AND enable = 1
    ", [$id]);

    $row = $query->row();

    if (!$row) {
        return false; 
    }

    $sql = $row->value;   
    $result = $this->db->query($sql);  

    if(!$result) {
        return false;
    }

    $this->db->query("
        UPDATE requests 
        SET enable = 0
        WHERE id_requests = ?
    ", [$id]);

    return true; 
}

public function deleteRequest($id)
{
    $query = 
    $this->db->query(" UPDATE requests 
        SET enable = 0
        WHERE id_requests = ?
        AND enable = 1
    ", [$id]);

    return $this->db->query($query);  
}

public function getBlogs($limit, $offset) {

    $query = $this->db->query("
            SELECT bg.*, emp.first_name, emp.last_name
            FROM blogs_admin AS bg
            LEFT JOIN employee AS emp 
            ON bg.id_employee = emp.id
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
    $this->db->from('blogs_admin'); // Use your actual blog table name
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

public function individualBlogAdmin($id) {
    $this->db->select('bs.id_blogs_admin, bs.id_mundiales, bs.id_employee, bs.title, bs.image, bs.content, bs.created_at, bs.likes, em.first_name');
    $this->db->from('blogs_admin AS bs');
    $this->db->join('employee AS em', 'bs.id_employee = em.id', 'left');
    $this->db->where('bs.id_blogs_admin', $id);
    $this->db->where('bs.enable', 1);
    
    $query = $this->db->get();
    $row = $query->row();
    return $this->fixImagePath($row);

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
    $query = $this->db->get('blog_categories');
    return $query->result();
}

public function getMundiales() {
    $query = $this->db->query("   SELECT m.*, c.* 
                FROM mundiales AS m
                LEFT JOIN countries AS c
                ON m.id_countries = c.id_countries 
                WHERE m.enable = 1 AND c.enable = 1
                ;");

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

public function getAdminBlogs($id) {

    $query = $this->db->query("
            SELECT bga.*, ctry.name, ctry.flag, mun.background_img 
            FROM blogs_admin AS bga
            LEFT JOIN mundiales AS mun 
            ON bga.id_mundiales = mun.id_mundiales
            LEFT JOIN countries as ctry
            ON mun.id_countries = ctry.id_countries 
            WHERE bga.id_mundiales = $id
            AND bga.enable = 1
            ORDER BY bga.created_at DESC
        ");
    return $query->result();
}
public function toggle_like_admin($id_blogs_admin, $id_employee)
{
    // Check if the user has already liked the post
    $exists = $this->db
        ->where('id_blogs_admin', $id_blogs_admin)
        ->where('id', $id_employee)
        ->get('likes_admin')
        ->row();

    if ($exists) {
        // Unlike (delete the like)
        $this->db->where('id_blogs_admin', $id_blogs_admin)
                 ->where('id', $id_employee)
                 ->delete('likes_admin');
        return false;
    } else {
        // Like (insert new like)
        $data = [
            'id_blogs_admin' => $id_blogs_admin,
            'id'    => $id_employee
        ];
        $this->db->insert('likes_admin', $data);
        return true;
    }
}

public function get_like_count_admin($id_blogs_admin)
{
    return $this->db
        ->where('id_blogs_admin', $id_blogs_admin)
        ->count_all_results('likes_admin');
}

public function getWorldCupYears() {

    $query = 
        $this->db->query(" SELECT DISTINCT year
            FROM mundiales
            WHERE enable = 1
            ;");

    return $query->result();
}

public function getCountries() {

    $query = 
        $this->db->query(" SELECT DISTINCT id_countries, name
            FROM countries
            WHERE enable = 1
            ;");

    return $query->result();
}

public function getBlogsFiltered($limit, $offset, $filters = []) {
    $this->db->select('b.*, e.first_name, e.last_name, m.year');
    $this->db->from('blogs_admin b');
    $this->db->join('employee e', 'b.id_employee = e.id', 'left');
    $this->db->join('mundiales m', 'b.id_mundiales = m.id_mundiales', 'left');

    // Sorting
    if (!empty($filters['sort'])) {
        switch($filters['sort']) {
            case 'date_asc':
                $this->db->order_by('b.created_at', 'ASC');
                break;
            case 'date_desc':
                $this->db->order_by('b.created_at', 'DESC');
                break;
            case 'likes':
                $this->db->order_by('b.likes', 'DESC');
                break;
            default:
                $this->db->order_by('b.created_at', 'DESC');
        }
    } else {
        $this->db->order_by('b.created_at', 'DESC');
    }

    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    return $query->result();
}

private function fixImagePath($row)
{
    if ($row && !empty($row->image)) {
        if (strpos($row->image, 'uploads/') !== 0) {
            $row->image = 'uploads/' . $row->image;
        }
    }
    return $row;
}

public function filterBlogs($category = null, $year = null, $country = null, $user = null)
{
    $this->db->select('blogs_admin.*, employee.first_name, employee.last_name, categories.name AS category_name, mundiales.year AS worldcup_year, countries.name AS country_name');
    $this->db->from('blogs_admin');
    $this->db->join('employee', 'employee.id = blogs_admin.id_employee');
    $this->db->join('categories', 'categories.id = blogs_admin.id_blog_categories', 'left');
    $this->db->join('mundiales', 'mundiales.id = blogs_admin.id_mundiales', 'left');
    $this->db->join('countries', 'countries.id = blogs_admin.country_id', 'left'); // if you have a country column

    if ($category) $this->db->where('blogs_admin.id_blog_categories', $category);
    if ($year)     $this->db->where('mundiales.year', $year);
    if ($country)  $this->db->where('countries.id', $country);
    if ($user)     $this->db->where('blogs_admin.id_employee', $user);

    $this->db->order_by('blogs_admin.created_at', 'DESC');
    $query = $this->db->get();
    return $query->result();
}


}
