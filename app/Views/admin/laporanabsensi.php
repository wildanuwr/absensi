<?= $this->extend('admin/includes/template') ?>
<?= $this->section('konten') ?>
<div id="content-wrapper" class="d-flex flex-column">

    <div class="container-fluid">
        <h4>Laporan Absensi</h4>

        <!-- Formulir Pilih Tanggal -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="<?= site_url('admin/laporanabsensi') ?>" method="get" class="form-inline">
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="start_date" class="sr-only">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="<?= esc($start_date ?? '') ?>" required>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="end_date" class="sr-only">Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="<?= esc($end_date ?? '') ?>" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary mb-2">Tampilkan Laporan</button>
                        <a href="<?= site_url('admin/download-laporan?' . http_build_query(['start_date' => $start_date, 'end_date' => $end_date])) ?>" class="btn btn-success mb-2">Unduh Laporan</a>
                    </div>
                </form>
            </div>
        </div>


        <!-- Tabel Laporan -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
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
                            <td><?= esc($row['jam_masuk']) ?: '--'; ?></td>
                            <td>
                                <?php if ($row['jam_masuk'] && $row['foto_masuk']): ?>
                                    <img src="<?= base_url('img/foto/' . esc($row['foto_masuk'])) ?>" alt="Foto Masuk" width="50" height="50" class="img-thumbnail">
                                <?php else: ?>
                                    <span>--</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['lokasi_masuk']): ?>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= esc($row['lokasi_masuk']); ?>" target="_blank">
                                        Lihat di Maps
                                    </a>
                                <?php else: ?>
                                    <span>--</span>
                                <?php endif; ?>
                            </td>
                            <td style="color: 
    <?php
                        if ($row['jam_keluar'] == 'izin') {
                            echo '#ffc107'; // Kuning untuk status izin
                        } elseif (!empty($row['jam_keluar'])) {
                            echo '#28a745'; // Hijau jika ada jam keluar yang lain (selain izin)
                        } elseif ($row['jam_keluar'] == 'libur') {
                            echo '#dc3545'; // Merah jika tidak ada jam keluar
                        } else {
                            echo '#343a40'; // Hitam jika data kosong (belum ada jam keluar)
                        }
    ?>">
                                <?= esc($row['jam_keluar']) ?: 'Belum Keluar'; ?>
                            </td>
                            <td>
                                <?php if ($row['jam_keluar'] && $row['foto_keluar']): ?>
                                    <img src="<?= base_url('img/foto/' . esc($row['foto_keluar'])) ?>" alt="Foto Pulang" width="50" height="50" class="img-thumbnail">
                                <?php else: ?>
                                    <span>--</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['lokasi_keluar']): ?>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= esc($row['lokasi_keluar']); ?>" target="_blank">
                                        Lihat di Maps
                                    </a>
                                <?php else: ?>
                                    <span>--</span>
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
</div>
<?= $this->endSection() ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>