<?php

class SmtpModel extends CI_Model
{
    public function saveEmailLog($data)
    {
        return $this->db->insert(
            'email_logs',
            $data
        );
    }

    public function getEmails()
    {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get('email_logs')
            ->result();
    }
}