<?php

class Kuesioner_model extends CI_model
{

    public function getKategori()
    {
        return $this->db->get('kategori')->result_array();
    }

    public function getAspekKepuasan()
    {
        return $this->db->get('aspek_kepuasan')->result_array();
    }

    public function getAksi()
    {
        return $this->db->get_where('aksi', ['nipd'=> $this->session->userdata('nipd')])->result_array();
        // return $this->db->get('aksi')->result_array();
    }

    public function getSoalGuru()
    {
        return $this->db->get('soal_guru')->result_array();
    }

    public function getSoalKepuasan()
    {
        return $this->db->get('v_s_kepuasan')->result_array();
    }


    public function getAllSurveiGuru()
    {
        return $this->db->get('survei_guru')->result_array();
    }

    public function getAllSurveiKegiatan()
    {
        return $this->db->get('kuesioner')->result_array();
    }

    public function addKategori()
    {
        $data = [
            "kategori" => $this->input->post('kategori', true)
        ];

        $this->db->insert('kategori', $data);
    }
    public function addAspekKepuasan()
    {
        $data = [
            "aspek" => $this->input->post('aspek', true)
        ];

        $this->db->insert('aspek_kepuasan', $data);
    }

    public function deleteKategori($id)
    {
        $this->db->trans_start();
			$this->db->delete('soal', array('id_kategori' => $id));
			$this->db->delete('kategori', array('id_kategori' => $id));
		$this->db->trans_complete();
    }

    public function deleteAspek($id)
    {
        $this->db->trans_start();
			$this->db->delete('aspek', array('id_aspek' => $id));
			$this->db->delete('soal_aspek', array('id_aspek' => $id));
		$this->db->trans_complete();
    }

    public function addPertanyaan($id_kat,$pertanyaan)
    {
        $this->db->trans_start();
        
        $result = array();
            foreach($pertanyaan AS $key => $val){
                 $result[] = array(
                  'id_kategori'  	=> $id_kat,
                  'soal'  	=> $_POST['pertanyaan'][$key]
                 );
            }      
        //MULTIPLE INSERT TO DETAIL TABLE
        $this->db->insert_batch('soal', $result);
    $this->db->trans_complete();
    }

    public function addPertanyaanGuru($pertanyaan)
    {
        $this->db->trans_start();
        
        $result = array();
            foreach($pertanyaan AS $key => $val){
                 $result[] = array(
                  'pertanyaan'  	=> $_POST['pertanyaan'][$key]
                 );
            }      
        //MULTIPLE INSERT TO DETAIL TABLE
        $this->db->insert_batch('soal_guru', $result);
    $this->db->trans_complete();
    }

    public function addPertanyaanKepuasan($pertanyaan, $opsi1, $opsi2, $opsi3, $opsi4)
    {
        $this->db->trans_start();
			$result = array();
			    foreach($pertanyaan AS $key => $val){
				     $result[] = array(
				      'pertanyaan'  	=> $_POST['pertanyaan'][$key],
				      'id_aspek_kepuasan'  	=> $_POST['aspek'],
				      'opsi_1'  	=> $_POST['opsi_1'],
				      'opsi_2'  	=> $_POST['opsi_2'],
				      'opsi_3'  	=> $_POST['opsi_3'],
				      'opsi_4'  	=> $_POST['opsi_4']
				     );
			    }      
			//MULTIPLE INSERT TO DETAIL TABLE
			$this->db->insert_batch('soal_kepuasan', $result);
		$this->db->trans_complete();
    }

    public function addKuesioner($judul,$deskripsi,$kategori,$kelas,$mulai,$selesai)
    {
       $this->db->trans_start();
			//INSERT TO PACKAGE
			$data = [
                "judul" => $judul,
                "deskripsi" => $deskripsi,
                'tgl_mulai' => date_format(date_create($mulai), 'Y-m-d'),
                'tgl_selesai' => date_format(date_create($selesai), 'Y-m-d'),
                "id_kategori" => $kategori
            ];
    
            $this->db->insert('kuesioner', $data);
			//GET ID PACKAGE
			$package_id = $this->db->insert_id();
			$result = array();
			    foreach($kelas AS $key => $val){
				     $result[] = array(
				      'id_kuesioner'  	=> $package_id,
				      'id_kelas'  	=> $_POST['kelas'][$key]
				     );
			    }      
			//MULTIPLE INSERT TO DETAIL TABLE
			$this->db->insert_batch('kuesioner_tmp', $result);
		$this->db->trans_complete();
    }

    public function addKuesionerGuru($guru,$mulai,$selesai)
    {
       $this->db->trans_start();
			//INSERT TO PACKAGE
			$data = [
                'tgl_mulai' => date_format(date_create($mulai), 'Y-m-d'),
                'tgl_selesai' => date_format(date_create($selesai), 'Y-m-d')
            ];
    
            $this->db->insert('survei_guru', $data);
			//GET ID PACKAGE
			$package_id = $this->db->insert_id();
			$result = array();
			    foreach($guru AS $key => $val){
				     $result[] = array(
				      'id_survei_guru'  	=> $package_id,
				      'id_guru'  	=> $_POST['guru'][$key]
				     );
			    }      
			//MULTIPLE INSERT TO DETAIL TABLE
			$this->db->insert_batch('survei_guru_tmp', $result);
		$this->db->trans_complete();
    }

    public function addKuesionerKepuasan($kelas,$mulai,$selesai)
    {
       $this->db->trans_start();
			//INSERT TO PACKAGE
			$data = [
                'tgl_mulai' => date_format(date_create($mulai), 'Y-m-d'),
                'tgl_selesai' => date_format(date_create($selesai), 'Y-m-d')
            ];
    
            $this->db->insert('survei_kepuasan', $data);
			//GET ID PACKAGE
			$package_id = $this->db->insert_id();
			$result = array();
			    foreach($kelas AS $key => $val){
				     $result[] = array(
				      'id_survei_kepuasan'  	=> $package_id,
				      'id_kelas'  	=> $_POST['kelas'][$key]
				     );
			    }      
			//MULTIPLE INSERT TO DETAIL TABLE
			$this->db->insert_batch('survei_kepuasan_tmp', $result);
		$this->db->trans_complete();
    }

    public function deleteKuesionerGuru($id)
    {
        $this->db->trans_start();
			$this->db->delete('survei_guru', array('id_survei_guru' => $id));
			$this->db->delete('survei_guru_tmp', array('id_survei_guru' => $id));
		$this->db->trans_complete();
    }

    public function deleteKuesioner($id)
    {
        $this->db->trans_start();
			$this->db->delete('kuesioner', array('id_kuesioner' => $id));
			$this->db->delete('kuesioner_tmp', array('id_kuesioner' => $id));
		$this->db->trans_complete();
    }

//----------------------------------------------------------------------------------------- SISWA Down here
    public function getSurveiKegiatan()
    {
        $id_kelas=$this->session->userdata('id_kelas');
        $nipd=$this->session->userdata('nipd');
        $this->db->select('*');
        $this->db->from('v_s_kegiatan');
        $this->db->where('id_kelas',$id_kelas); 
        $this->db->where('id_kuesioner NOT IN(select id_kuesioner from aksi where nipd='.$nipd.')'); 
        $query = $this->db->get(); 
        return $query->result_array();  
        // return $this->db->get_where('v_s_kegiatan', ['id_kelas'=> $this->session->userdata('id_kelas')])->result_array();
    }

    public function getSurveiGuru()
    {
        // $id_kelas=$this->session->userdata('id_kelas');
        // $nipd=$this->session->userdata('nipd');
        // $this->db->select('*');
        // $this->db->from('v_s_guru');
        // $this->db->where('id_kelas',$id_kelas); 
        // $this->db->where('id_guru NOT IN(select id_guru from aksi_guru where nipd='.$nipd.')'); 
        // $query = $this->db->get(); 
        // return $query->result_array();  
        return $this->db->get_where('v_s_guru', ['id_kelas'=> $this->session->userdata('id_kelas')])->result_array();
    }

    public function getDetailSurveiKegiatan($id)
    {
        return $this->db->get_where('kuesioner', ['id_kuesioner'=>$id])->result_array();
    }

    public function getSoalKegiatan($id)
    {
        return $this->db->get_where('v_soal_kegiatan', ['id_kuesioner'=>$id,'id_kelas'=>$this->session->userdata('id_kelas')])->result_array();
    }

    public function getSoalKegiatan_Admin($id)
    {
        $this->db->select('*');
        $this->db->from('v_soal_kegiatan');
        $this->db->where('id_kuesioner',$id);
        $this->db->group_by('id_soal'); 
        $query = $this->db->get(); 
        return $query->result_array();  
    }

    public function getThisGuru($idk,$idg)
    {
        return $this->db->get_where('v_s_guru', ['id_survei_guru'=>$idk,'id_guru'=>$idg,'id_kelas'=>$this->session->userdata('id_kelas')])->result_array();
    }

    public function addAksi($nipd, $date, $kuesioner, $opsi)
    {
        $this->db->trans_start();
			//INSERT TO PACKAGE
			$data = [
                "nipd" => $nipd,
                "tgl_isi" => $date,
                "id_kuesioner" => $kuesioner
            ];
    
            $this->db->insert('aksi', $data);
			//GET ID PACKAGE
			$package_id = $this->db->insert_id();
			$result = array();
			    foreach($opsi AS $key => $val){
				     $result[] = array(
				      'id_aksi'  	=> $package_id,
				      'soal_no'  	=> $key,
				      'id_opsi'  	=> $_POST['opsi'][$key]
				     );
			    }      
			//MULTIPLE INSERT TO DETAIL TABLE
			$this->db->insert_batch('aksi_tmp', $result);
		$this->db->trans_complete();
    }

    public function addAksiGuru($nipd, $date, $kuesioner, $opsi,$guru)
    {
        $this->db->trans_start();
			//INSERT TO PACKAGE
			$data = [
                "nipd" => $nipd,
                "tgl_isi" => $date,
                "id_survei_guru" => $kuesioner,
                "id_guru" => $guru
            ];
    
            $this->db->insert('aksi_guru', $data);
			//GET ID PACKAGE
			$package_id = $this->db->insert_id();
			$result = array();
			    foreach($opsi AS $key => $val){
				     $result[] = array(
				      'id_aksi_guru'  	=> $package_id,
				      'soal_no'  	=> $key,
				      'id_opsi'  	=> $_POST['opsi'][$key]
				     );
			    }      
			//MULTIPLE INSERT TO DETAIL TABLE
			$this->db->insert_batch('aksi_guru_tmp', $result);
		$this->db->trans_complete();
    }

    public function aksi($id)
    {
        return $this->db->get_where('aksi', ['id_kuesioner'=>$id,'nipd'=>$this->session->userdata('nipd')])->result_array();
    }

    public function aksiGuru($idg)
    {
        return $this->db->get_where('aksi_guru', ['id_guru'=>$idg,'nipd'=>$this->session->userdata('nipd')])->result_array();
    }

 //Dashboard   

    public function getJumlahKuesioner()
    {
        $this->db->select('count(id_kuesioner) as jumlah');
        $this->db->from('kuesioner');
        $query = $this->db->get(); 
        return $query->result_array();  
    }


//-------------------------------------------------------------------------------------> End of Dashboard


}