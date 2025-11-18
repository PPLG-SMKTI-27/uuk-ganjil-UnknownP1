<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController {

    // TAMPILKAN HALAMAN LOGIN
    public function loginPage() {
        include __DIR__ . '/../Views/login.php';
    }

    // PROSES LOGIN
    public function prosesLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?login");
            exit;
        }

        $model = new User();

        // Ambil NAMA (bukan email)
        $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        // LOGIN PAKAI NAMA
        $user = $model->loginNama($nama, $password);

        if ($user) {
            session_start();
            
            // DEBUG: Tampilkan data user yang login
            echo "<!-- DEBUG USER DATA: ";
            print_r($user);
            echo " -->";
            
            // TAMBAHKAN id_user di session dengan fallback
            $_SESSION['id_user'] = $user['id'] ?? $user['ID'] ?? 0;
            $_SESSION['nama'] = $user['Nama'] ?? $user['nama'] ?? '';
            $_SESSION['jabatan'] = $user['Jabatan'] ?? $user['jabatan'] ?? '';

            echo "<script>alert('Login berhasil! Jabatan: " . $_SESSION['jabatan'] . "');</script>";
            // ARAHKAN KE index.php (HOME) DULU, BUKAN LANGSUNG KE FORM
            echo "<script>window.location='index.php'</script>";
            exit;
        } else {
            echo "<script>alert('Login gagal! Nama atau Password salah');</script>";
            echo "<script>window.location='index.php?login'</script>";
            exit;
        }
    }

    // TAMPILKAN FORM REGISTER
    public function registerPage() {
        include __DIR__ . '/../Views/register.php';
    }

    // PROSES REGISTER
    public function prosesRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?register");
            exit;
        }

        $model = new User();

        $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $jabatan = isset($_POST['jabatan']) ? trim($_POST['jabatan']) : 'siswa';

        // simple validation
        if ($nama === '' || $email === '' || $password === '') {
            echo "<script>alert('Lengkapi semua kolom!');</script>";
            echo "<script>window.location='index.php?register'</script>";
            exit;
        }

        $result = $model->register($nama, $email, $password, $jabatan);

        if ($result) {
            echo "<script>alert('Akun berhasil dibuat! Silakan login.');</script>";
            echo "<script>window.location='index.php?login'</script>";
            exit;
        } else {
            echo "<script>alert('Email sudah digunakan!');</script>";
            echo "<script>window.location='index.php?register'</script>";
            exit;
        }
    }

    // LOGOUT
    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php?login");
        exit;
    }
}