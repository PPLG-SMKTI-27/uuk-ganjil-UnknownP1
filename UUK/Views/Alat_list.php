<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Alat Lab</title>
    <style>
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; }
        .header { display: flex; justify-content: between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .btn-primary { background: #007bff; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .actions { display: flex; gap: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 Kelola Data Alat Lab</h1>
            <a href="index.php" class="btn btn-success">🏠 Kembali ke Home</a>
        </div>

        <a href="index.php?alat&action=tambah" class="btn btn-primary">➕ Tambah Alat Baru</a>

        <?php if (empty($data)): ?>
            <p style="margin-top: 20px; color: #666;">Belum ada data alat.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Stok Tersedia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= $row['stok'] ?> unit</td>
                        <td class="actions">
                            <a href="index.php?alat&action=edit&id=<?= $row['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                            <a href="index.php?alat&action=hapus&id=<?= $row['id'] ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Yakin ingin menghapus alat <?= htmlspecialchars($row['nama']) ?>?')">
                               🗑️ Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>