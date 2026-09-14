<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FileTransferController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('FileTransferModel', 'ftp');
        $this->load->helper('download');
    }

    public function index()
    {
        $data['files'] = $this->ftp->getFiles();

        $this->load->view('templates/header_home');
        $this->load->view('home/ftp_center_fifa_v', $data);
        $this->load->view('templates/footer');
    }

    public function uploadFile()
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 20480; // 20 MB

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile'))
        {
            echo $this->upload->display_errors();
            return;
        }

        $fileData = $this->upload->data();

        $data = [
            'file_name'   => $fileData['file_name'],
            'file_path'   => 'uploads/' . $fileData['file_name'],
            'file_size'   => $fileData['file_size'],
            'uploaded_by' => $this->session->userdata('id')
        ];

        $this->ftp->insertFile($data);

        redirect('ftp_center');
    }

    public function download($id)
    {
        $file = $this->ftp->getFileById($id);

        force_download(
            FCPATH . $file->file_path,
            NULL
        );
    }
}
