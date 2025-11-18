<?php
require_once __DIR__ . '/../Models/Alat.php';

class AlatController {

    public function __construct() {
        // Tambahkan session_start() di constructor
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ==================================================
    // PROTEKSI: HANYA KAPRODI YANG BOLEH CRUD
    // ==================================================
    private function onlyKaprodi() {
        // DEBUG: Cek session di controller
        error_log("onlyKaprodi() called - Session jabatan: " . ($_SESSION['jabatan'] ?? 'NULL'));
        
        if (!isset($_SESSION['jabatan']) || $_SESSION['jabatan'] !== 'kaprodi') {
            echo "<script>alert('Akses ditolak! Hanya Kaprodi yang boleh mengelola data alat. Jabatan Anda: " . ($_SESSION['jabatan'] ?? 'Tidak terdeteksi') . "');</script>";
            echo "<script>window.location='index.php'</script>";
            exit;
        }
    }

    // ==================================================
    // TAMPILKAN LIST ALAT
    // ==================================================
    public function index() {
        $this->onlyKaprodi();

        $alat = new Alat();
        $data = $alat->getAll();

        include __DIR__ . '/../Views/alat_list.php';
    }

    // ==================================================
    // FORM TAMBAH ALAT
    // ==================================================
    public function tambah() {
        $this->onlyKaprodi();

        include __DIR__ . '/../Views/alat_form.php';
    }

    // ==================================================
    // SIMPAN DATA ALAT
    // ==================================================
    public function simpan() {
        $this->onlyKaprodi();

        if (!isset($_POST['nama']) || !isset($_POST['stok'])) {
            echo "<script>alert('Data tidak lengkap');</script>";
            echo "<script>window.location='index.php?alat'</script>";
            exit;
        }

        $alat = new Alat();
        $alat->setNama($_POST['nama']);
        $alat->setStok($_POST['stok']);
        $alat->save();

        echo "<script>alert('Data alat berhasil disimpan!');</script>";
        echo "<script>window.location='index.php?alat'</script>";
        exit;
    }

    // ==================================================
    // FORM EDIT ALAT
    // ==================================================
    public function edit($id) {
        $this->onlyKaprodi();

        $alat = new Alat();
        $data = $alat->getById($id);
        
        if (!$data) {
            echo "<script>alert('Data alat tidak ditemukan');</script>";
            echo "<script>window.location='index.php?alat'</script>";
            exit;
        }

        include __DIR__ . '/../Views/alat_form.php';
    }

    // ==================================================
    // UPDATE DATA ALAT
    // ==================================================
    public function update($id) {
        $this->onlyKaprodi();

        if (!isset($_POST['nama']) || !isset($_POST['stok'])) {
            echo "<script>alert('Data tidak lengkap');</script>";
            echo "<script>window.location='index.php?alat'</script>";
            exit;
        }

        $alat = new Alat();
        $alat->setId($id);
        $alat->setNama($_POST['nama']);
        $alat->setStok($_POST['stok']);
        $alat->update();

        echo "<script>alert('Data alat berhasil diupdate!');</script>";
        echo "<script>window.location='index.php?alat'</script>";
        exit;
    }

    // ==================================================
    // HAPUS DATA ALAT
    // ==================================================
    public function hapus($id) {
        $this->onlyKaprodi();

        $alat = new Alat();
        $result = $alat->delete($id);
        
        if ($result) {
            echo "<script>alert('Data alat berhasil dihapus!');</script>";
        } else {
            echo "<script>alert('Gagal menghapus data alat!');</script>";
        }
        
        echo "<script>window.location='index.php?alat'</script>";
        exit;
    }
}