<?php
require_once __DIR__ . '/../Models/Peminjaman.php';
require_once __DIR__ . '/../Models/Alat.php';

class PeminjamanController {

    public function __construct() {
        // Tidak perlu session_start() di sini
    }

    // ==================================================
    // PROTEKSI: HARUS LOGIN
    // ==================================================
    private function mustLogin() {
        // Cek apakah user sudah login (gunakan nama atau jabatan sebagai patokan)
        if (!isset($_SESSION['nama']) || !isset($_SESSION['jabatan'])) {
            echo "<script>alert('Silakan login terlebih dahulu');</script>";
            echo "<script>window.location='index.php'</script>";
            exit;
        }
    }

    // ==================================================
    // TAMPILKAN PEMINJAMAN USER YANG LOGIN (SISWA)
    // ==================================================
    public function index() {
        $this->mustLogin();

        $model = new Peminjaman();
        
        // Jika id_user tidak ada di session, gunakan nama sebagai alternatif
        if (isset($_SESSION['id_user'])) {
            $data = $model->getByUserId($_SESSION['id_user']);
        } else {
            // Fallback: cari berdasarkan nama peminjam
            $data = $model->getByNamaPeminjam($_SESSION['nama']);
        }
        
        include __DIR__ . '/../Views/peminjaman_list.php';
    }

    // ==================================================
    // FORM PEMINJAMAN UNTUK SISWA
    // ==================================================
    public function form() {
        $this->mustLogin();

        $alat = new Alat();
        $dataAlat = $alat->getAll();
        include __DIR__ . '/../Views/peminjaman_form.php';
    }

    // ==================================================
    // PROSES PEMINJAMAN
    // ==================================================
    public function proses() {
        $this->mustLogin();

        // Gunakan id_user jika ada, jika tidak gunakan session nama
        $id_user = $_SESSION['id_user'] ?? 0; // Default ke 0 jika tidak ada
        $id_alat = $_POST['id_alat'];
        $nama = $_POST['nama_peminjam'];
        $kelas = $_POST['kelas'];
        $kejuruan = $_POST['kejuruan'];
        $jumlah = $_POST['jumlah'];

        $model = new Peminjaman();
        $result = $model->pinjam($id_user, $id_alat, $nama, $kelas, $kejuruan, $jumlah);

        if ($result === "stok_kurang") {
            echo "<script>alert('Stok barang tidak mencukupi!');</script>";
        } else {
            echo "<script>alert('Peminjaman berhasil!');</script>";
        }

        echo "<script>window.location='index.php?peminjaman'</script>";
    }

    // ==================================================
    // RIWAYAT SEMUA PEMINJAMAN (UNTUK KAPRODI)
    // ==================================================
    public function riwayat() {
        $this->mustLogin();
        
        if ($_SESSION['jabatan'] !== 'kaprodi') {
            echo "<script>alert('Akses ditolak! Hanya Kaprodi yang boleh melihat riwayat');</script>";
            echo "<script>window.location='index.php'</script>";
            exit;
        }

        $model = new Peminjaman();
        $data = $model->getAll();
        include __DIR__ . '/../Views/riwayat_peminjaman.php';
    }

    // ==================================================
    // BATALKAN/HAPUS PEMINJAMAN
    // ==================================================
    public function batalkan($id) {
        $this->mustLogin();

        $model = new Peminjaman();
        
        // Cek apakah user adalah pemilik peminjaman atau kaprodi
        $peminjaman = $model->getById($id);
        if (!$peminjaman) {
            echo "<script>alert('Data peminjaman tidak ditemukan');</script>";
            echo "<script>window.location='index.php?peminjaman'</script>";
            exit;
        }

        // Jika bukan kaprodi, cek apakah user adalah pemilik peminjaman
        if ($_SESSION['jabatan'] !== 'kaprodi') {
            // Cek berdasarkan nama peminjam jika id_user tidak ada
            if (isset($_SESSION['id_user'])) {
                $is_owner = ($peminjaman['id_user'] == $_SESSION['id_user']);
            } else {
                $is_owner = ($peminjaman['nama_peminjam'] == $_SESSION['nama']);
            }
            
            if (!$is_owner) {
                echo "<script>alert('Anda tidak berhak membatalkan peminjaman ini');</script>";
                echo "<script>window.location='index.php?peminjaman'</script>";
                exit;
            }
        }

        $result = $model->hapus($id);

        if ($result) {
            echo "<script>alert('Peminjaman berhasil dibatalkan dan stok dikembalikan!');</script>";
        } else {
            echo "<script>alert('Gagal membatalkan peminjaman!');</script>";
        }

        // Redirect berdasarkan role
        if ($_SESSION['jabatan'] === 'kaprodi') {
            echo "<script>window.location='index.php?peminjaman&action=riwayat'</script>";
        } else {
            echo "<script>window.location='index.php?peminjaman'</script>";
        }
    }
}