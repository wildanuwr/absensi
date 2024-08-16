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
    <form action="<?= base_url('user/submit_izin/')?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <!-- Formulir data pengguna -->
        <input type="hidden" class="form-control" id="jam_masuk" name="jam_masuk" value="izin">
        <input type="hidden" class="form-control" id="jam_keluar" name="jam_keluar" value="izin">
        <div class="form-group boxed">
            <label for="Nama">Nama</label>
            <input type="text read-only" class="form-control" id="Nama" name="Nama" value="<?= esc($user['Nama']) ?>" readonly>
        </div>
        <div class="form-group boxed">
            <label for="tanggal">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>
        <div class="form-group boxed">
            <label for="keterangan">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" required>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        <!-- * App Capsule -->
    </form>
</div>