<?php if (!defined('BASEPATH')) exit('No Direct Script Access Allowed');

class ModelPinjam extends CI_Model
{

    //manip table pinjam
    public function simpanPinjam($data)
    {
        $this->db->insert('pinjam1', $data);
    }

    public function selectData($table, $where)
    {
        return $this->db->get($table, $where);
    }

    public function updateData($data, $where)
    {
        $this->db->update('pinjam1', $data, $where);
    }

    public function deleteData($tabel, $where)
    {
        $this->db->delete($tabel, $where);
    }

    public function joinData()
    {
        $this->db->select('*');
        $this->db->from('pinjam1');
        $this->db->join('detail_pinjam1', 'detail_pinjam1.no_pinjam=pinjam1.no_pinjam', 'Right');
        
        return $this->db->get()->result_array();
    }


    //manip tabel detai pinjam
    public function simpanDetail($idbooking, $nopinjam)
    {
        $sql = "INSERT INTO detail_pinjam1 (no_pinjam,id_buku) SELECT pinjam1.no_pinjam,booking_detail1.id_buku FROM pinjam1, booking_detail1 WHERE booking_detail1.id_booking=$idbooking AND pinjam1.no_pinjam='$nopinjam'";
        $this->db->query($sql);
    }
}
