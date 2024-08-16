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
            <div class="col-3 d-flex ">
                <div class="mb-3">
                    <label for="file">Upload User</label>
                    <input type="file" id="file" name="file" multiple />
                </div>
                <input class="btn btn-primary" type="submit" value="Submit">
            </div>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
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
                        <td><?= esc($lok['id']); ?></td>
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
<?= $this->EndSection() ?>