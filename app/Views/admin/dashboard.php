<?= $this->extend('admin/includes/template') ?>
<?= $this->section('konten') ?>
<div id="content-wrapper" class="d-flex flex-column">

    <div class="container-fluid">
        <h1>Dashoard</h1>
        <h4>Absensi Hari ini</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Foto Masuk</th>
                    <th>Lokasi Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Foto Pulang</th>
                    <th>Lokasi Pulang</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($absensi)) : ?>
                    <?php foreach ($absensi as $row) : ?>
                        <tr>
                            <td><?= esc($row['Nama']); ?></td>
                            <td><?= esc($row['tanggal']); ?></td>
                            <td><?= esc($row['jam_masuk']); ?></td>
                            <td>
                                <?php if ($row['jam_masuk'] && $row['foto_masuk']): ?>
                                    <img src="<?= base_url('img/foto/' . esc($row['foto_masuk'])) ?>" alt="Foto Masuk" width="50" height="50">
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['lokasi_masuk']): ?>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= esc($row['lokasi_masuk']); ?>" target="_blank">
                                        Lihat di Maps
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($row['jam_keluar']); ?></td>
                            <td>
                                <?php if ($row['jam_keluar'] && $row['foto_pulang']): ?>
                                    <img src="<?= base_url('img/foto/' . esc($row['foto_pulang'])) ?>" alt="Foto Pulang" width="50" height="50">
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['lokasi_pulang']): ?>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= esc($row['lokasi_pulang']); ?>" target="_blank">
                                        Lihat di Maps
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8">Tidak ada data absensi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?= $this->EndSection() ?>