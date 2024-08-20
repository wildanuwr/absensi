<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <i class="fas fa-arrow-left fa-2x"></i>
        </a>
    </div>
    <div class="pageTitle"><?= $judul ?></div>
    <div class="right"></div>
</div>
<!-- * App Header -->
<div id="appCapsule" class="pt-3 mt-5 m-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($absensi)) : ?>
                <?php foreach ($absensi as $row) : ?>
                    <tr>
                        <td><?= esc($row['Nama']); ?></td>
                        <td><?= esc($row['tanggal']); ?></td>
                        <td style="color: 
    <?php
                    if ($row['jam_masuk'] == 'izin') {
                        echo '#ffc107'; 
                    } elseif (!empty($row['jam_masuk'])) {
                        echo '#28a745'; 
                    } elseif ($row['jam_masuk'] == 'libur') {
                        echo '#dc3545';
                    } else {
                        echo '#343a40'; 
                    }
    ?>">
                            <?= esc($row['jam_masuk']) ?: 'Belum Masuk'; ?>
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