<?php

class FileTransferModel extends CI_Model
{
    public function getFiles()
    {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get('ftp_files')
            ->result();
    }

    public function insertFile($data)
    {
        return $this->db->insert(
            'ftp_files',
            $data
        );
    }

    public function getFileById($id)
    {
        return $this->db
            ->where('id_file', $id)
            ->get('ftp_files')
            ->row();
    }
}