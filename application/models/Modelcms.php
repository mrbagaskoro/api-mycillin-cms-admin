<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modelcms extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function list_new_partner() {
    $query = $this->db->query("SELECT * FROM partner_account pa inner join partner_profile pr on pa.user_id=pr.user_id WHERE status_id='03'");
    return $query->result();
  }

  public function list_all_partner() {
    $query = $this->db->query("SELECT * FROM partner_account pa inner join partner_profile pr on pa.user_id=pr.user_id");
    return $query->result();
  }

  public function detail_partner($user_id='') {
    $query = $this->db->query("SELECT * FROM partner_account pa inner join partner_profile pr on pa.user_id=pr.user_id WHERE pa.user_id='$user_id'");
    return $query->row();
  }

  public function init_partner($data) {
    $where['user_id'] = $data['user_id'];

    $update['full_name'] = strtoupper($data['full_name']);
    $update['no_SIP'] = $data['sip_no'];
    $update['SIP_berakhir'] = date("Y-m-d", strtotime($data['sip_exp']));
    $update['no_STR'] = $data['str_no'];
    $update['STR_berakhir'] = date("Y-m-d", strtotime($data['str_exp']));

    $query = $this->db->update('partner_profile', $update, $where);
    return $query?$this->detail_partner($data['user_id']):false;
  }

  public function avatar_partner($data, $user_id) {
    $where['user_id'] = $user_id;

    $query = $this->db->update('partner_account', $data, $where);
    return $query?$this->detail_partner($user_id):false;
  }

  public function doc_partner($data, $user_id) {
    $where['user_id'] = $user_id;

    $query = $this->db->update('partner_profile', $data, $where);
    return $query?$this->detail_partner($user_id):false;
  }

  public function status_partner($data, $user_id) {
    $where['user_id'] = $user_id;

    $query = $this->db->update('partner_account', $data, $where);
    return $query?$this->detail_partner($user_id):false;
  }
}