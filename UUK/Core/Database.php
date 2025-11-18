<?php
class Database {

    protected $conn;

    public function __construct() {
        $servername = "localhost";
        $username   = "root";
        $password   = "";
        $database   = "UUK_Faris";

        $this->conn = new mysqli($servername, $username, $password, $database);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }
}
