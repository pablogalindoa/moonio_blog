<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommentModel extends CI_Model {

    public function addComment($data) {
        return $this->db->insert('comments', $data);
    }

    public function getCommentsByBlog($blog_id) {
        $this->db->select('comments.*, employee.first_name, employee.last_name');
        $this->db->from('comments');
        $this->db->join('employee', 'employee.id = comments.user_id', 'left');
        $this->db->where('blog_id', $blog_id);
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get()->result();
    }
}
