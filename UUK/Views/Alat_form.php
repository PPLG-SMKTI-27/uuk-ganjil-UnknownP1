<h2><?= isset($alat) ? "Edit Alat" : "Tambah Alat" ?></h2>

<form method="POST" action="index.php?save_alat">
    <?php if (isset($alat)): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($alat['Id_alat']) ?>">
    <?php endif; ?>

    Nama Barang:<br>
    <input type="text" name="nama_barang" value="<?= isset($alat) ? htmlspecialchars($alat['Nama_barang']) : '' ?>" required><br><br>

    Stok:<br>
    <input type="number" name="stok" value="<?= isset($alat) ? (int)$alat['Stok'] : 0 ?>" min="0" required><br><br>

    Kondisi:<br>
    <input type="text" name="kondisi" value="<?= isset($alat) ? htmlspecialchars($alat['Kondisi_barang']) : '' ?>"><br><br>

    Jenis:<br>
    <input type="text" name="jenis" value="<?= isset($alat) ? htmlspecialchars($alat['Jenis_barang']) : '' ?>"><br><br>

    <button type="submit"><?= isset($alat) ? 'Update' : 'Simpan' ?></button>
</form>
