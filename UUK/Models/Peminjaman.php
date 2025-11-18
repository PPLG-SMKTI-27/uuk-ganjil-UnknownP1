<?php
require_once __DIR__ . '/../core/Database.php';

class Peminjaman extends Database {

    // Ambil semua data peminjaman
    public function getAll() {
        $sql = "SELECT p.*, b.Nama_barang 
                FROM peminjaman p
                JOIN data_barang b ON p.Id_Alat = b.Id_alat
                ORDER BY p.Id_Peminjaman DESC";
        return $this->conn->query($sql);
    }

    // Ambil semua barang
    public function getBarang() {
        return $this->conn->query("SELECT * FROM data_barang ORDER BY Nama_barang ASC");
    }

    // Tambah peminjaman + kurangi stok otomatis
    public function pinjam($id_user, $id_alat, $nama_peminjam, $kelas, $kejuruan, $jumlah) {
        
        // Cek stok barang
        $cek = $this->conn->query("SELECT Stok FROM data_barang WHERE Id_alat=$id_alat");
        $stok = $cek->fetch_assoc()['Stok'];

        if ($stok < $jumlah) {
            return "stok_kurang";
        }

        // Kurangi stok barang
        $this->conn->query("UPDATE data_barang 
                            SET Stok = Stok - $jumlah 
                            WHERE Id_alat = $id_alat");

        // Insert peminjaman
        $sql = "INSERT INTO peminjaman 
                (Id_User, Id_Alat, Nama_Peminjam, Kelas, Kejuruan, Jumlah)
                VALUES 
                ('$id_user', '$id_alat', '$nama_peminjam', '$kelas', '$kejuruan', '$jumlah')";

        return $this->conn->query($sql);
    }

    // Hapus peminjaman + kembalikan stok otomatis
    public function hapus($id_peminjaman) {

        // Ambil info pinjaman
        $get = $this->conn->query("SELECT Id_Alat, Jumlah 
                                   FROM peminjaman 
                                   WHERE Id_Peminjaman=$id_peminjaman");
        $data = $get->fetch_assoc();

        $id_alat = $data['Id_Alat'];
        $jumlah = $data['Jumlah'];

        // Kembalikan stok barang
        $this->conn->query("UPDATE data_barang 
                            SET Stok = Stok + $jumlah 
                            WHERE Id_alat = $id_alat");

        // Hapus data
        return $this->conn->query("DELETE FROM peminjaman WHERE Id_Peminjaman=$id_peminjaman");
    }
}
