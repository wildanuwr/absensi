<?= $this->extend('admin/includes/template') ?>
<?= $this->section('konten') ?>
<div id="content-wrapper" class="d-flex flex-column">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <div class="container-fluid">
        <h1>Manajemen User</h1>
        <div class="row d-flex">
            <div class="col">
                <?= $this->include('admin/includes/formadduser') ?>
                <button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#exampleModal">+ Tambah</button>
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
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">Jabatan</th>
                <th scope="col">No Hp</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Aksi</th> <!-- Tambahkan kolom aksi -->
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users) && is_array($users)): ?>
                <?php foreach ($users as $index => $userItem): ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= esc($userItem['Nama']) ?></td>
                        <td><?= esc($userItem['jabatan']) ?></td>
                        <td><?= esc($userItem['no_hp']) ?></td>
                        <td><?= esc($userItem['email']) ?></td>
                        <td>
                            <?php
                            if ($userItem['role'] == 1) {
                                echo 'Admin';
                            } elseif ($userItem['role'] == 2) {
                                echo 'User';
                            } else {
                                echo 'Unknown';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/profile/' . $userItem['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= base_url('admin/deleteUser/' . $userItem['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Tidak ada data pengguna yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>
<?= $this->EndSection() ?>