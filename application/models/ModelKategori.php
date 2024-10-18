<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class ModelKategori extends CI_Model
{

	public function getAll()
	{
		return $this->db->get('kategori1');
	}

	public function getWhere($where = Null)
	{
		return $this->db->get('kategori1', $where);
	}

	public function inData($data)
	{
		$this->db->insert('kategori1', $data);
	}

	public function upData($data, $where)
	{
		$this->db->update('kategori1', $data, $where);
	}

	public function delData($where)
	{
		$this->db->delete('kategori1', $where);
	}
}
