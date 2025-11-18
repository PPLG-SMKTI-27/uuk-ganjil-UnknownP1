<h2>Buat Akun Baru</h2>

<form method="POST" action="index.php?proses_register">

    <label>Nama</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Jabatan</label><br>
    <select name="jabatan">
        <option value="siswa">Siswa</option>
        <option value="kaprodi">Kaprodi</option>
    </select>
    <br><br>

    <button type="submit">Daftar</button>
</form>
