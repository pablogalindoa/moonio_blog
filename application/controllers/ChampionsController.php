<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChampionsController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ChampionsModel');
    }

    public function timeline() {
    $data['champions'] = $this->ChampionsModel->getChampions();
    $data['most_titles'] = $this->ChampionsModel->getMostTitles();

    // DEBUG
    // die(var_dump($data));

    $this->load->view('champions/champions_timeline', $data);
}

}
