<?php

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kelas_model');
        $this->load->model('Guru_model');
        $this->load->model('Kuesioner_model');
        $this->load->model('Siswa_model');
        $this->load->model('login_model');
        $this->load->library('form_validation');
        
        if($this->login_model->is_role() != "1"){
            redirect("forbidden/");
        }
    }

    public function index()
    {
        $data['page']='surveiGuru';
        $this->load->view('templates/header', $data);
        $this->load->view('admin/surveiGuru');  
        $this->load->view('templates/footer');  
    }
// Dashboard
    public function surveiGuru()
    {
        $data['page']='surveiGuru';
        $this->load->view('templates/header', $data);
        $this->load->view('admin/surveiGuru');  
        $this->load->view('templates/footer');  
    }

    public function surveiKegiatan()
    {
        $data['page']='surveiKegiatan';
        $this->load->view('templates/header', $data);
        $this->load->view('admin/surveiKegiatan');
        $this->load->view('templates/footer');  
    }

//---------------------------------------------------End Dashboard

// KuesionerGuru

    public function KuesionerGuru()
    {
        $data['page']='tbhKuesionerGuru';
        $data['guru'] = $this->Guru_model->getAll();
        $data['survei'] = $this->Kuesioner_model->getAllSurveiGuru();
        $this->load->view('templates/header', $data);
        $this->load->view('admin/tbhKuesionerGuru', $data);
        $this->load->view('templates/footer');  
    }

    public function tbhKuesionerGuru()
    {
        $guru = $this->input->post('guru',TRUE);
		$mulai = $this->input->post('mulai',TRUE);
		$selesai = $this->input->post('selesai',TRUE);
        $this->Kuesioner_model->AddKuesionerGuru($guru, $mulai,$selesai);
        $this->session->set_flashdata('flash', 'Ditambahkan');
		redirect('admin/KuesionerGuru');
    }

    public function hapusKuesionerGuru($id)
    {
        $this->Kuesioner_model->deleteKuesionerGuru($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/KuesionerGuru');
    }

//-------------------------------------------------- End Kuesioner Guru

//KuesionerKegiatan
    public function KuesionerKegiatan()
    {
        $data['page']='tbhKuesionerKegiatan';
        $data['kategori']=$this->Kuesioner_model->getKategori();
        $data['survei'] = $this->Kuesioner_model->getAllSurveiKegiatan();
        $data['kelas']=$this->Kelas_model->getAll();

        $this->load->view('templates/header', $data);
        $this->load->view('admin/tbhKuesionerKegiatan', $data);
        $this->load->view('templates/footer');  
    }

    public function tbhKuesioner()
    {
        $judul = $this->input->post('judul',TRUE);
		$deskripsi = $this->input->post('deskripsi',TRUE);
		$kategori = $this->input->post('kategori',TRUE);
		$kelas = $this->input->post('kelas',TRUE);
		$mulai = $this->input->post('mulai',TRUE);
		$selesai = $this->input->post('selesai',TRUE);
        $this->Kuesioner_model->AddKuesioner($judul,$deskripsi,$kategori,$kelas,$mulai,$selesai);
        $this->session->set_flashdata('flash', 'Ditambahkan');
		redirect('admin/KuesionerKegiatan');
    }

    public function preview_k($id)
    {
        $data['page']='tbhKuesionerKegiatan';
        $data['detail']=$this->Kuesioner_model->getDetailSurveiKegiatan($id);
        $data['soal']=$this->Kuesioner_model->getSoalKegiatan_Admin($id);

        $this->load->view('templates/header', $data);
        $this->load->view('admin/preview_k', $data);
        $this->load->view('templates/footer');  
    }

    public function hapusKuesioner($id)
    {
        $this->Kuesioner_model->deleteKuesioner($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/KuesionerKegiatan');
    }
//-------------------------------------------------- End Kuesioner Kegiatan
    

// Siswa

    public function dataSiswa()
    {
        $data['page']='tbhDataSiswa';
        $data['kelas'] = $this->Kelas_model->getAll();
        $this->load->view('templates/header', $data);
        $this->load->view('admin/tbhDataSiswa', $data);
        $this->load->view('templates/footer');  
    }

    public function tbhDataSiswa()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('nipd', 'NIPD', 'required|numeric');
        $this->form_validation->set_rules('kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('namaIbu', 'NamaIbu', 'required');

        if ($this->form_validation->run() == false) {
            redirect('admin/dataSiswa');
        } else {
            $this->Siswa_model->Add();
            $this->session->set_flashdata('flash', 'Ditambahkan');
            redirect('admin/dataSiswa');
        }
    }

    public function tbhMultiDataSiswa()
    {
        $kelas = $this->input->post('kelas',TRUE);
		$nama = $this->input->post('siswa',TRUE);
		$nipd = $this->input->post('nipd',TRUE);
		$namaIbu = $this->input->post('namaIbu',TRUE);
        $this->Siswa_model->addMulti($kelas,$nama,$nipd,$namaIbu);
        $this->session->set_flashdata('flash', 'Ditambahkan');
		redirect('admin/dataSiswa');
    }

    public function siswaNonRegis()
    {
        $data['page']='siswaNonRegis';
        $data['nonregis'] = $this->Siswa_model->getNonRegis();

        $this->load->view('templates/header', $data);
        $this->load->view('admin/siswaNonRegis', $data);
        $this->load->view('templates/footer');  
    }

    public function userSiswa()
    {
        $data['page']='userSiswa';
        $data['user'] = $this->Siswa_model->getUser();
        $this->load->view('templates/header', $data);
        $this->load->view('admin/userSiswa', $data);
        $this->load->view('templates/footer');  
    }

    public function hapusSiswa($id)
    {
        $this->Siswa_model->delete($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/siswaNonRegis');
    }

    public function hapusUserSiswa($id)
    {
        $this->Siswa_model->deleteUser($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/userSiswa');
    }
//-------------------------------------------------- End Siswa

//Kelas

    public function kelas()
    {
        $data['page']='kelas';

        $data['kelas'] = $this->Kelas_model->getAll();

        $this->load->view('templates/header', $data);
        $this->load->view('admin/kelas', $data);
        $this->load->view('templates/footer');  
    }

    public function tbhKelas()
    {
        $this->form_validation->set_rules('kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric');

        if ($this->form_validation->run() == false) {
            redirect('admin/kelas');
        } else {
            $this->Kelas_model->Add();
            $this->session->set_flashdata('flash', 'Ditambahkan');
            redirect('admin/kelas');
        }
    }

    public function hapusKelas($id)
    {
        $this->Kelas_model->delete($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/kelas');
    }
//-------------------------------------------------- End Kelas

//Guru

    public function guru()
    {
        $data['page']='guru';

        $data['guru'] = $this->Guru_model->getAll();
        $data['kelas'] = $this->Kelas_model->getAll();


        $this->load->view('templates/header', $data);
        $this->load->view('admin/guru', $data);
        $this->load->view('templates/footer');  
    }

    public function tbhAmpuan()
    {
        $nama = $this->input->post('nama',TRUE);
		$kelas = $this->input->post('kelas',TRUE);
        $this->Guru_model->Add($nama,$kelas);
        $this->session->set_flashdata('flash', 'Ditambahkan');
		redirect('admin/guru');
    }

    public function hapusAmpuan($id)
    {
        $this->Guru_model->delete($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/guru');
    }

//-------------------------------------------------- End Guru   

//Pertanyaan

    public function pertanyaan()
    {
        $data['page']='pertanyaan';

        $data['kategori'] = $this->Kuesioner_model->getKategori();

        $this->load->view('templates/header', $data);
        $this->load->view('admin/pertanyaan', $data);
        $this->load->view('templates/footer');  
    }

    public function tbhKategori()
    {
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');

        if ($this->form_validation->run() == false) {
            redirect('admin/pertanyaan');
        } else {
            $this->Kuesioner_model->addKategori();
            $this->session->set_flashdata('flash', 'Ditambahkan');
            redirect('admin/pertanyaan');
        }
    }

    public function hapusKategori($id)
    {
        $this->Kuesioner_model->deleteKategori($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/pertanyaan');
    }

    public function tbhPertanyaan()
    {
        $id_kat = $this->input->post('id_kategori',TRUE);
		$pertanyaan = $this->input->post('pertanyaan',TRUE);
        $this->Kuesioner_model->AddPertanyaan($id_kat,$pertanyaan);
        $this->session->set_flashdata('flash', 'Ditambahkan');
		redirect('admin/pertanyaan');
    }

//-------------------------------------------------- End Pertanyaan

//Aspek Penilaian Guru

    public function aspekGuru()
    {
        $data['page']='aspekGuru';
        $data['aspek'] = $this->Kuesioner_model->getAspek();
        $this->load->view('templates/header', $data);
        $this->load->view('admin/aspek', $data);
        $this->load->view('templates/footer');

    }

    public function tbhAspek()
    {
        $this->form_validation->set_rules('aspek', 'Aspek', 'required');

        if ($this->form_validation->run() == false) {
            redirect('admin/aspekGuru');
        } else {
            $this->Kuesioner_model->addAspek();
            $this->session->set_flashdata('flash', 'Ditambahkan');
            redirect('admin/aspekGuru');
        }
    }

    public function tbhPertanyaanAspek()
    {
        $id_asp = $this->input->post('id_aspek',TRUE);
		$pertanyaan = $this->input->post('pertanyaan',TRUE);
        $this->Kuesioner_model->AddPertanyaanAspek($id_asp,$pertanyaan);
        $this->session->set_flashdata('flash', 'Ditambahkan');
		redirect('admin/aspekGuru');
    }
//-------------------------------------------------- End Pertanyaan  

//Admin
    public function admin()
    {
        $data['page']='tbhAdmin';
        $this->load->view('templates/header', $data);
        $this->load->view('admin/tbhDataAdmin');  
        $this->load->view('templates/footer');  
    }

    public function dftAdmin()
    {
        $data['page']='dftAdmin';
        $data['admin'] = $this->login_model->getAdmin();
        $this->load->view('templates/header', $data);
        $this->load->view('admin/dftAdmin',$data);  
        $this->load->view('templates/footer');  
    }

    public function tbhAdmin()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('nipd', 'Nama', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == false) {
            redirect('admin/admin');
        } else {   
            $this->login_model->addUserAdmin();
            $this->session->set_flashdata('flash', 'Ditambahkan');
            redirect('admin/admin');
        }
    }

    public function hapusAdmin($id)
    {
        $this->login_model->deleteAdmin($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/dftAdmin');
    }

//-------------------------------------------------- End Admin
}

