<?php if (!defined('BASEPATH')) exit('No Direct Script Access Allowed');

class Pinjam extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['ModelUser', 'ModelBuku', 'ModelPinjam']);
        cek_login();
        cek_user();
    }

    public function index()
    {
        $data['judul'] = "Data Pinjam";
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['pinjam'] = $this->ModelPinjam->joinData();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('pinjam/data-pinjam', $data);
        $this->load->view('templates/footer');
    }

    public function daftarBooking()
    {
        $data['judul'] = "Daftar Booking";
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['pinjam'] = $this->db->query("select*from booking1")->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('booking/daftar-booking', $data);
        $this->load->view('templates/footer');
    }

    public function bookingDetail()
    {
        $id_booking = $this->uri->segment(3);
        $data['judul'] = "Booking Detail";
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['agt_booking'] = $this->db->query("select*from booking1 b, user1 u where b.id_user=u.id and b.id_booking='$id_booking'")->result_array();
        $data['detail'] = $this->db->query("select id_buku,judul_buku,pengarang,penerbit,tahun_terbit from booking_detail1 d, buku1 b where d.id_buku=b.id and d.id_booking='$id_booking'")->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('booking/booking-detail', $data);
        $this->load->view('templates/footer');
    }

    public function pinjamAct()
    {
        $id_booking = $this->uri->segment(3);
        $lama = $this->input->post('lama', TRUE);

        $bo = $this->db->query("SELECT*FROM booking1 WHERE id_booking='$id_booking'")->row();

        $tglsekarang = date('Y-m-d');
        $no_pinjam = $this->ModelBooking->kodeotomatis('pinjam', 'no_pinjam');
        $databooking = [
            'no_pinjam' => $no_pinjam,
            'id_booking' => $id_booking,
            'tgl_pinjam' => $tglsekarang,
            'id_user' => $bo->id_user,
            'tgl_kembali' => date('Y-m-d', strtotime('+' . $lama . ' days', strtotime($tglsekarang))),
            'tgl_pengembalian' => '0000-00-00',
            'status' => 'Pinjam',
            'total_denda' => 0
        ];
        $this->ModelPinjam->simpanPinjam($databooking);
        $this->ModelPinjam->simpanDetail($id_booking, $no_pinjam);
        $denda = $this->input->post('denda', TRUE);
        $this->db->query("update detail_pinjam1 set denda='$denda'");

        //hapus Data booking yang bukunya diambil untuk dipinjam
        $this->ModelPinjam->deleteData('booking', ['id_booking' => $id_booking]);
        $this->ModelPinjam->deleteData('booking_detail', ['id_booking' => $id_booking]);
        //$this->db->query("DELETE FROM booking WHERE id_booking='$id_booking'");

        //update dibooking dan dipinjam pada tabel buku saat buku yang dibooking diambil untuk dipinjam
        $this->db->query("UPDATE buku1, detail_pinjam1 SET buku.dipinjam=buku.dipinjam+1, buku.dibooking=buku.dibooking-1 WHERE buku.id=detail_pinjam.id_buku");

        $this->session->set_flashdata('pesan', '<div class="alert alert-message alert-success" role="alert">Data Peminjaman Berhasil Disimpan</div>');
        redirect(base_url() . 'pinjam');
    }

    public function ubahStatus()
    {
        $id_buku = $this->uri->segment(3);
        $no_pinjam = $this->uri->segment(4);
        $where = ['id_buku' => $this->uri->segment(3),];

        $tgl = date('Y-m-d');
        $status = 'Kembali';
        //update status menjadi kembali pada saat buku dikembalikan
        $this->db->query("UPDATE pinjam1, detail_pinjam1 SET pinjam1.status='$status', pinjam1.tgl_pengembalian='$tgl' WHERE detail_pinjam1.id_buku='$id_buku' AND pinjam1.no_pinjam='$no_pinjam'");

        //update stok dan dipinjam pada tabel buku
        $this->db->query("UPDATE buku1, detail_pinjam1 SET buku1.dipinjam=buku1.dipinjam-1, buku1.stok=buku1.stok+1 WHERE buku1.id=detail_pinjam1.id_buku");

        $this->session->set_flashdata('pesan', '<div class="alert alert-message alert-success" role="alert"></div>');
        redirect(base_url('pinjam'));
    }
}
