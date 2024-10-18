<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelBuku extends CI_Model
{
    //manajemen buku
    public function getBuku()
    {
        return $this->db->get('buku1');
    }

    public function getLimitBuku()
    {
        $this->db->limit(5);
        return $this->db->get('buku1');
    }

    public function bukuWhere($where)
    {
        return $this->db->get_where('buku1', $where);
    }

    public function simpanBuku($data = null)
    {
        $this->db->insert('buku1', $data);
    }

    public function updateBuku($data = null, $where = null)
    {
        $this->db->update('buku1', $data, $where);
    }

    public function hapusBuku($where = null)
    {
        $this->db->delete('buku1', $where);
    }

    public function total($field, $where)
    {
        $this->db->select_sum($field);
        if (!empty($where) && count($where) > 0) {
            $this->db->where($where);
        }
        $this->db->from('buku1');
        return $this->db->get()->row($field);
    }

    public function bukuTotalRecord()
    {
        $this->db->from('buku1');
        return $this->db->count_all_results();
    }

    public function bukuLimit($batas, $awal = 0)
    {
        $this->db->order_by('id', 'DESC');
        $this->db->limit($batas, $awal);

        return $this->db->get('buku1');
    }

    //manajemen kategori
    public function getKategori()
    {
        return $this->db->get('kategori1');
    }

    public function kategoriWhere($where)
    {
        return $this->db->get_where('kategori1', $where);
    }

    public function simpanKategori($data = null)
    {
        $this->db->insert('kategori1', $data);
    }

    public function hapusKategori($where = null)
    {
        $this->db->delete('kategori1', $where);
    }

    public function updateKategori($where = null, $data = null)
    {
        $this->db->update('kategori1', $data, $where);
    }

    //join
    public function joinKategoriBuku($where)
    {
        //$this->db->select('buku.id_kategori,kategori.kategori');
        $this->db->from('buku1');
        $this->db->join('kategori1', 'kategori1.id = buku1.id_kategori');
        $this->db->where($where);
        return $this->db->get();
    }
}
