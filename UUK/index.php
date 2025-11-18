<?php
require_once __DIR__ . "/Controllers/AlatController.php";
require_once __DIR__ . "/Controllers/PeminjamanController.php";
require_once __DIR__ . "/Controllers/AuthController.php";

session_start();

// DEBUG: Tampilkan session data
echo "<!-- DEBUG SESSION: ";
print_r($_SESSION);
echo " -->";

$alatController = new AlatController();
$peminjamanController = new PeminjamanController();
$authController = new AuthController();

// =========================
// AUTH
// =========================
if (isset($_GET['login'])) {
    $authController->loginPage();
}
elseif (isset($_GET['proses_login'])) {
    $authController->prosesLogin();
}
elseif (isset($_GET['register'])) {
    $authController->registerPage();
}
elseif (isset($_GET['proses_register'])) {
    $authController->prosesRegister();
}
elseif (isset($_GET['logout'])) {
    $authController->logout();
}

// =========================
// KAPRODI CRUD ALAT (Struktur Baru)
// =========================
elseif (isset($_GET['alat'])) {
    $action = $_GET['action'] ?? 'index';
    
    switch ($action) {
        case 'tambah':
            $alatController->tambah();
            break;
        case 'simpan':
            $alatController->simpan();
            break;
        case 'edit':
            $id = $_GET['id'] ?? 0;
            $alatController->edit($id);
            break;
        case 'update':
            $id = $_GET['id'] ?? 0;
            $alatController->update($id);
            break;
        case 'hapus':
            $id = $_GET['id'] ?? 0;
            $alatController->hapus($id);
            break;
        default:
            $alatController->index();
            break;
    }
}

// =========================
// PEMINJAMAN (Struktur Baru)
// =========================
elseif (isset($_GET['peminjaman'])) {
    $action = $_GET['action'] ?? 'index';
    
    switch ($action) {
        case 'form':
            $peminjamanController->form();
            break;
        case 'proses':
            $peminjamanController->proses();
            break;
        case 'riwayat':
            $peminjamanController->riwayat();
            break;
        case 'batalkan':
            $id = $_GET['id'] ?? 0;
            $peminjamanController->batalkan($id);
            break;
        default:
            $peminjamanController->index();
            break;
    }
}

// =========================
// DEFAULT HOME
// =========================
else {
    // DEBUG SESSION
    echo "<!-- DEBUG SESSION DATA: ";
    echo "nama: " . ($_SESSION['nama'] ?? 'NULL') . ", ";
    echo "jabatan: " . ($_SESSION['jabatan'] ?? 'NULL') . ", ";
    echo "id_user: " . ($_SESSION['id_user'] ?? 'NULL');
    echo " -->";

    echo "
    <div style='max-width: 800px; margin: 0 auto; padding: 20px;'>
        <h2>Selamat datang di Aplikasi Peminjaman Alat Lab</h2>";

    if (!isset($_SESSION['nama'])) {
        echo "
        <div style='margin: 20px 0;'>
            <a href='index.php?login' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Login</a>
            <a href='index.php?register' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;'>Daftar</a>
        </div>";
    } else {
        // TAMPILKAN INFO DEBUG DI HALAMAN
        echo "
        <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; border: 2px solid red;'>
            <h3>🔍 DEBUG INFO:</h3>
            <strong>Nama:</strong> {$_SESSION['nama']}<br>
            <strong>Jabatan:</strong> {$_SESSION['jabatan']}<br>
            <strong>ID User:</strong> " . ($_SESSION['id_user'] ?? 'NULL') . "<br>
            <strong>Kondisi:</strong> " . ($_SESSION['jabatan'] == 'kaprodi' ? 'KAPRODI DETECTED' : 'BUKAN KAPRODI') . "
        </div>";

        echo "<div style='margin: 20px 0;'>";
        
        // TEST: Tampilkan link untuk semua role dulu
        echo "<h3>🔗 Test Links (Available for All):</h3>";
        echo "<a href='index.php?alat' style='display: inline-block; padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>📦 TEST: Kelola Alat</a><br>";
        echo "<a href='index.php?peminjaman&action=riwayat' style='display: inline-block; padding: 10px 20px; background: #ffc107; color: black; text-decoration: none; border-radius: 5px; margin: 5px;'>📋 TEST: Riwayat Peminjaman</a><br><br>";
        
        // Menu berdasarkan role
        if ($_SESSION['jabatan'] == 'kaprodi') {
            echo "<h3>🎯 Menu Kaprodi:</h3>";
            echo "<a href='index.php?alat' style='display: inline-block; padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>📦 Kelola Alat</a><br>";
            echo "<a href='index.php?peminjaman&action=riwayat' style='display: inline-block; padding: 10px 20px; background: #ffc107; color: black; text-decoration: none; border-radius: 5px; margin: 5px;'>📋 Riwayat Peminjaman</a>";
        } else {
            echo "<h3>🎯 Menu Siswa:</h3>";
            echo "<a href='index.php?peminjaman&action=form' style='display: inline-block; padding: 10px 20px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>🔧 Pinjam Alat</a><br>";
            echo "<a href='index.php?peminjaman' style='display: inline-block; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>📖 Peminjaman Saya</a>";
        }
        echo "</div>";

        echo "<a href='index.php?logout' style='color: #dc3545; text-decoration: none;'>🚪 Logout</a>";
    }
    echo "</div>";
}