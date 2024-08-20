<?= $this->extend('admin/includes/template') ?>
<?= $this->section('konten') ?>
<div id="content-wrapper" class="d-flex flex-column">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <div class="container-fluid">
        <h1><?= $judul ?></h1>
        <div class="row d-flex">
            <div class="col">
                <?= $this->include('admin/includes/modallokasi') ?>
                <button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#ModalLokasi">+ Tambah</button>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama Lokasi</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Radius (meter)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lokasi) && is_array($lokasi)): ?>
                    <?php foreach ($lokasi as $lok): ?>
                        <tr>
                            <td><?= esc($lok['nama_lokasi']); ?></td>
                            <td><?= esc($lok['latitude']); ?></td>
                            <td><?= esc($lok['longitude']); ?></td>
                            <td><?= esc($lok['radius']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Tidak ada data lokasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->EndSection() ?>