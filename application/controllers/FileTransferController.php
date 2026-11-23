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
        $this->load->view('home/ftp_center_v', $data);
        $this->load->view('templates/footer');
    }

    public function uploadFile()
    {
        $chatUpload = $this->input->post('chat_upload') === '1';
        if ($chatUpload && !$this->session->userdata('id')) {
            $this->output->set_status_header(401)->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Inicia sesión para subir archivos.']));
            return;
        }
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = $chatUpload ? 'pdf|txt|csv|zip|jpg|jpeg|png|gif|webp|doc|docx|xls|xlsx|ppt|pptx|mp3|mp4|wav|ogg' : '*';
        $config['encrypt_name'] = $chatUpload;
        $config['max_size'] = 20480; // 20 MB

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile'))
        {
            if ($chatUpload) {
                $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => strip_tags($this->upload->display_errors())]));
            } else { echo $this->upload->display_errors(); }
            return;
        }

        $fileData = $this->upload->data();

        $data = [
            'file_name'   => $fileData['file_name'],
            'file_path'   => 'uploads/' . $fileData['file_name'],
            'file_size'   => $fileData['file_size'],
            'uploaded_by' => $this->session->userdata('id')
        ];

        $saved = $this->ftp->insertFile($data);
        if ($chatUpload) {
            $id = $this->db->insert_id();
            $this->output->set_status_header($saved ? 200 : 500)->set_content_type('application/json')->set_output(json_encode([
                'status' => $saved ? 'ok' : 'error',
                'url' => site_url('ftp/download/' . $id),
                'message' => $saved ? '' : 'No se pudo guardar el archivo.'
            ]));
            return;
        }

        redirect('ftp_center');
    }

    public function download($id)
    {
        $file = $this->ftp->getFileById($id);

        if (!$file) { show_404(); return; }

        force_download(
            FCPATH . $file->file_path,
            NULL
        );
    }
}
