<?php

class Guru_model extends CI_model
{
    public function getAll()
    {
        return $this->db->get('guru')->result_array();
    }
    
    public function getById()
    {
        
    }

    public function add($nama, $kelas)
    {
        $this->db->trans_start();
			//INSERT TO PACKAGE
			$data = [
                "nama" => $nama
            ];
    
            $this->db->insert('guru', $data);
			//GET ID PACKAGE
			$package_id = $this->db->insert_id();
			$result = array();
			    foreach($kelas AS $key => $val){
				     $result[] = array(
				      'id_guru'  	=> $package_id,
				      'id_kelas'  	=> $_POST['kelas'][$key]
				     );
			    }      
			//MULTIPLE INSERT TO DETAIL TABLE
			$this->db->insert_batch('ampuan', $result);
		$this->db->trans_complete();
    }

    public function edit()
    {

    }

    public function delete($id)
    {
        $this->db->trans_start();
			$this->db->delete('ampuan', array('id_guru' => $id));
			$this->db->delete('guru', array('id_guru' => $id));
		$this->db->trans_complete();
    }
}