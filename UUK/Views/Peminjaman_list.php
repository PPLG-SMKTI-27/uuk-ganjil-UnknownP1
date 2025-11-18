<h2>Riwayat Peminjaman</h2>
<a href="index.php?form_pinjam">Pinjam Alat</a>
<table border="1">
<tr><th>ID</th><th>Peminjam</th><th>Kelas</th><th>Jumlah</th></tr>
<?php foreach ($data as $row): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['nama_peminjam'] ?></td>
<td><?= $row['kelas'] ?></td>
<td><?= $row['jumlah'] ?></td>
</tr>
<?php endforeach ?>
</table>