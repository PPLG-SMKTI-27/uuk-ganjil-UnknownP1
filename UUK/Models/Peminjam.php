<?php
class Peminjam {
private $nama;
private $kelas;


public function __construct($nama, $kelas) {
$this->nama = $nama;
$this->kelas = $kelas;
}


public function getNama() { return $this->nama; }
public function getKelas() { return $this->kelas; }
}