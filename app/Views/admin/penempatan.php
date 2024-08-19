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
                <?= $this->include('admin/includes/modalpenempatan') ?>
                <button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#ModalPenempatan">+ Tambah</button>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Nama Lokasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_lokasi as $tempat): ?>
                    <tr>
                        <td><?= $tempat['id'] ?></td>
                        <td><?= $tempat['Nama'] ?></td>
                        <td><?= $tempat['nama_lokasi'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $this->EndSection() ?>
