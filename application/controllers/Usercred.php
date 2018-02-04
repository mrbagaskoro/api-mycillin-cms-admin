<?php
  defined('BASEPATH') or exit('No direct script access allowed');

  //require_once APPPATH . 'controllers/Controladmin.php';

  class Usercred extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('modelcms', 'mod');
    }

    public function index() {
      if($this->session->userdata('username')) {
          redirect('webmin', 'refresh');
      }
      $data['title'] = 'MyCillin | Login';
      $data['page'] = 'login';
      $this->load->view('/login.html', $data);
    }
    
    public function masuk() {
      $username = $this->input->post('username');
      $password = $this->input->post('password');

      /* script untuk membuat user pertama dengan enkripsi password
      $data = array(
          'username' => $username,
          'password' => $this->encrypt->encode($password)
        );

      $insert = $this->db->insert('m_user', $data);
      $this->session->set_flashdata('pesan', 'User berhasil ditambahkan.');
      redirect('login');
      */

      //$cek_user = $this->M_login->cek_user($username)->result();
      
      /*foreach($cek_user as $cu) {
        $pass_db = $this->encrypt->decode($cu->password);
      }*/ 

      if($username == 'admincms@mycillin.com' && $password == '@Sejagat_2018') {
        //$cekuser = $this->M_login->cek_user($username);
        //foreach($cekuser->result() as $sess) {
          $sess_data['id_user'] = 'id_user';
          $sess_data['username'] = $username;
          $sess_data['name'] = 'Admin CMS';
          $this->session->set_userdata($sess_data);
        //}
        redirect('webmin', 'refresh');
      } else {
        $this->session->set_flashdata('pesan', 'Maaf, kombinasi username dan password salah.');
        redirect('login', 'refresh');
      }
    }
    
    public function keluar() {
      $this->session->sess_destroy();
      $this->session->unset_userdata('username');
      redirect('login', 'refresh');
    }
    
  }