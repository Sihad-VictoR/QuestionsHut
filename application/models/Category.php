<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getCategoryData()
    {
        $result = $this->db->get('category');
        $row = $result->result_array();
        return $row;
    }
}
