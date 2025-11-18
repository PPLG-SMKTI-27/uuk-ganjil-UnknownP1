<?php
require_once __DIR__ . '/../core/Database.php';

class User extends Database {

    private function clean($value) {
        return mysqli_real_escape_string($this->conn, $value);
    }

    // LOGIN MENGGUNAKAN NAMA
    public function loginNama($nama, $password) {
        $nama = $this->clean($nama);
        $password = $this->clean($password);

        $sql = "SELECT * FROM user WHERE Nama='$nama' AND Password='$password'";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }

    // REGISTER USER
    public function register($nama, $email, $password, $jabatan) {
        $nama = $this->clean($nama);
        $email = $this->clean($email);
        $password = $this->clean($password);
        $jabatan = $this->clean($jabatan);

        // cek email duplikasi
        $check = $this->conn->query("SELECT * FROM user WHERE Email='$email'");
        if ($check && $check->num_rows > 0) {
            return false;
        }

        $sql = "INSERT INTO user (Nama, Email, Password, Jabatan)
                VALUES ('$nama', '$email', '$password', '$jabatan')";

        return $this->conn->query($sql);
    }
}
