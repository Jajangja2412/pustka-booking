<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ModelBooking extends CI_Model
{
    public function getData($table)
    {
        return $this->db->get($table)->row();
    }

    public function getDataWhere($table, $where)
    {
        $this->db->where($where);
        return $this->db->get($table);
    }

    public function getOrderByLimit($table, $order, $limit)
    {
        $this->db->order_by($order, 'desc');
        $this->db->limit($limit);
        return $this->db->get($table);
    }

    public function joinOrder($where)
    {
        $this->db->select('*');
        $this->db->from('booking1 bo');
        $this->db->join('booking_detail1 d', 'd.id_booking=bo.id_booking');
        $this->db->join('buku1 bu ', 'bu.id=d.id_buku');
        $this->db->where($where);

        return $this->db->get();
    }

    public function simpanDetail($where = null)
    {
        $sql = "INSERT INTO booking_detail1 (id_booking,id_buku) SELECT booking1.id_booking,temp1.id_buku FROM booking1, temp1 WHERE temp1.id_user=booking1.id_user AND booking1.id_user='$where'";
        $this->db->query($sql);
    }

    public function insertData($table, $data)
    {
        $this->db->insert($table, $data);
    }

    public function updateData($table, $data, $where)
    {
        $this->db->update($table, $data, $where);
    }

    public function deleteData($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function find($where)
    {
        //Query mencari record berdasarkan ID-nya
        $this->db->limit(1);
        return $this->db->get('buku1', $where);
    }

    public function kosongkanData($table)
    {
        return $this->db->truncate($table);
    }

    public function createTemp()
    {
        $this->db->query('CREATE TABLE IF NOT EXISTS temp1(id_booking varchar(12), tgl_booking DATETIME, email_user varchar(128), id_buku int)');
    }

    public function selectJoin()
    {
        $this->db->select('*');
        $this->db->from('booking1');
        $this->db->join('booking_detai1l', 'booking_detail1.id_booking=booking1.id_booking');
        $this->db->join('buku1', 'booking_detail1.id_buku=buku1.id');
        return $this->db->get();
    }

    public function showtemp($where)
    {
        return $this->db->get('temp1', $where);
    }

    public function kodeOtomatis($tabel, $key)
    {
        $this->db->select('right(' . $key . ',3) as kode', false);
        $this->db->order_by($key, 'desc');
        $this->db->limit(1);
        $query = $this->db->get($tabel);
        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->kode) + 1;
        } else {
            $kode = 1;
        }

        $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT);
        $kodejadi = date('dmY') . $kodemax;

        return $kodejadi;
    }
}
