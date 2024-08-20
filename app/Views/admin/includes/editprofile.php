<?= $this->extend('admin/includes/template') ?>
<?= $this->section('konten') ?>
<div id="content-wrapper" class="d-flex flex-column">
    <form action="<?= base_url('admin/updateUser/' . $user['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Menampilkan foto profil -->
        <div class="form-group">
            <label>Foto Profil</label><br>
            <?php if ($user['foto'] && file_exists(FCPATH . 'img/profile/' . $user['foto'])): ?>
                <img src="<?= base_url('img/profile/' . esc($user['foto'])); ?>" alt="Foto Profil" style="max-width: 150px; max-height: 150px; border-radius: 50%;">
            <?php else: ?>
                <p>Belum ada foto</p>
            <?php endif; ?>
        </div>

        <!-- Input untuk mengganti foto profil -->
        <div class="form-group">
            <label for="foto">Unggah Foto Profil Baru</label>
            <input type="file" class="form-control-file" id="foto" name="foto">
        </div>
        <!-- Formulir data pengguna -->
        <div class="form-group">
            <label for="Nama">Nama</label>
            <input type="text" class="form-control" id="Nama" name="Nama" value="<?= esc($user['Nama']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="jabatan">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= esc($user['jabatan']) ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="no_hp">No Hp</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= esc($user['no_hp']) ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Admin</option>
                <option value="2" <?= $user['role'] == 2 ? 'selected' : '' ?>>User</option>
            </select>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Close</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>