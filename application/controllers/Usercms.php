<?php
  defined('BASEPATH') or exit('No direct script access allowed');

  require_once APPPATH . 'controllers/Controladmin.php';

  class Usercms extends Controladmin {

    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('username')) {
            redirect('usercred/keluar', 'refresh');
        }
        $this->load->model('modelcms', 'mod');
    }

    public function index_get() {
        $data['title'] = 'Dashboard | Home';
        $data['name'] = $this->session->userdata('name');
        $data['username'] = $this->session->userdata('username');
        $this->load->view('/cms/head.html', $data);
        $this->load->view('/cms/content.html');
        $this->load->view('/cms/foot.html');
    }

    public function partner_data_get() {
        $data['partner'] = $this->mod->list_all_partner();
        $data['title'] = 'Dashboard | Partner';
        $data['name'] = $this->session->userdata('name');
        $data['username'] = $this->session->userdata('username');
        $this->load->view('/cms/head.html', $data);
        $this->load->view('/cms/partner-data.html', $data);
        $this->load->view('/cms/foot.html');
    }

    public function partner_detail_get($user_id='') {
        $data = array('partner_detail'=>$this->mod->detail_partner($user_id));
        if ($data['partner_detail'] == NULL) {
            $this->session->set_flashdata('pesan-partner', 'Maaf, Data tidak ditemukan.');
            redirect('webmin/partner/', 'refresh');
        }
        $data['title'] = 'Dashboard | Partner Detail';
        $data['name'] = $this->session->userdata('name');
        $data['username'] = $this->session->userdata('username');
        $this->load->view('/cms/head.html', $data);
        $this->load->view('/cms/partner-detail.html', $data);
        $this->load->view('/cms/foot.html');
    }

    public function partner_identity_save_post($user_id='') {
        $input['user_id'] = $user_id;
        $input['full_name'] = $this->input->post('full-name');
        $input['sip_no'] = $this->input->post('sip-no');
        $input['sip_exp'] = $this->input->post('sip-exp');
        $input['str_no'] = $this->input->post('str-no');
        $input['str_exp'] = $this->input->post('str-exp');
        $data = array('partner_detail'=>$this->mod->init_partner($input));
        $this->session->set_flashdata('pesan-save-identity', 'Berhasil simpan data');
        redirect('webmin/partner/'.$user_id, 'refresh');
    }

    public function partner_doc_save_post($user_id='') {
        $config['upload_path'] = UPLOAD_PATH_PROFILE;
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['max_size'] = 1024;
        $config['overwrite'] = true;

        $this->load->library('upload', $config);
        $upd = array();

        if (!empty($_FILES['partner-avatar']['name'])) {
            $config['file_name'] = 'partner_'.$user_id;

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('partner-avatar')) {
                $err = $this->upload->display_errors();
                var_dump($err.'di sini');
                die;
            }

            $up = $this->upload->data();
            $upd1['profile_photo'] = $up['file_name'];

            $data = array('partner_detail'=>$this->mod->avatar_partner($upd1, $user_id));
        }

        $config['upload_path'] = UPLOAD_PATH_DOCUMENT;
        $this->load->library('upload', $config);

        if (!empty($_FILES['partner-sip']['name'])) {
            $config['file_name'] = 'sip_'.$user_id;

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('partner-sip')) {
                $err = $this->upload->display_errors();
                var_dump($err.'2');
                die;
            }

            $up = $this->upload->data();
            $upd['photo_SIP'] = $up['file_name'];
        }

        $config['upload_path'] = UPLOAD_PATH_DOCUMENT;
        $this->load->library('upload', $config);

        if (!empty($_FILES['partner-str']['name'])) {
            $config['file_name'] = 'str_'.$user_id;

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('partner-str')) {
                $err = $this->upload->display_errors();
                var_dump($err.'3');
                die;
            }

            $up = $this->upload->data();
            $upd['photo_STR'] = $up['file_name'];
        }

        if (empty($upd)) {
            //$this->session->set_flashdata('pesan-save-doc', 'Maaf, Gagal simpan Dokumen.');
            redirect('webmin/partner/'.$user_id, 'refresh');
        }

        $data = array('partner_detail'=>$this->mod->doc_partner($upd, $user_id));
        redirect('webmin/partner/'.$user_id, 'refresh');
    }

    public function partner_status_save_post($user_id='') {
        $input['status_id'] = $this->input->post('radio-status');

        $data = array('partner_detail'=>$this->mod->status_partner($input, $user_id));
        $this->session->set_flashdata('pesan-save-status', 'Berhasil mengubah status');
        redirect('webmin/partner/'.$user_id, 'refresh');
    }
    
  }