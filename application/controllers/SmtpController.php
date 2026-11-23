<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SmtpController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('id')) { redirect('login'); }
        $this->load->model('SmtpModel', 'smtp');
    }

    public function index()
    {
        $this->load->view('templates/header_home');
        $this->load->view('home/smtp_fifa_v', ['recipients'=>$this->db->select('first_name,last_name,email')->where('enable',1)->get('employee')->result()]);
        $this->load->view('templates/footer');
    }

    public function send()
    {
        $to = $this->input->post('to');
        if (!$this->db->where('email', $to)->where('enable',1)->count_all_results('employee')) { show_error('Selecciona el correo de un usuario registrado.', 400); return; }
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');

        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = 465;

        $config['smtp_user'] = getenv('SMTP_USER');
        $config['smtp_pass'] = getenv('SMTP_PASSWORD');

        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';

        $config['newline'] = "\r\n";
        $config['crlf'] = "\r\n";

        $config['smtp_timeout'] = 30;

        $this->email->initialize($config);

        $this->email->from(
            getenv('SMTP_USER'),
            'FIFA Communication Center'
        );

        $this->email->to($to);

        $this->email->subject($subject);

        $this->email->message($message);

        if($this->email->send())
        {
            $data = [
                'sent_to' => $to,
                'subject' => $subject,
                'message' => $message,
                'sent_by' => $this->session->userdata('id')
            ];

            $this->smtp->saveEmailLog($data);

            echo "Correo enviado correctamente";
        }
        else
        {
            show_error('No se pudo enviar el correo. Revisa la configuración SMTP y la conexión del servidor.', 502);
        }
    }
}