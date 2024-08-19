<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (session()->getFlashdata('success')): ?>
    <script>
        alert('<?= session()->getFlashdata('success') ?>');
    </script>
<?php elseif (session()->getFlashdata('error')): ?>
    <script>
        alert('<?= session()->getFlashdata('error') ?>');
    </script>
<?php endif; ?>

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

<h1>Absen</h1>
<video id="video" width="320" height="240" autoplay></video>
<div id="map"></div>
<canvas id="canvas" style="display: none;"></canvas>
<img id="photo" alt="Captured Image" style="display: none;">

<form id="absenForm" enctype="multipart/form-data">
    <input type="hidden" name="Nama" value="<?= $user['Nama'] ?>">
    <input type="hidden" name="jam_masuk" value="<?= date('H:i:s') ?>">
    <input type="hidden" name="jam_keluar" id="jam_keluar">
    <input type="hidden" id="lokasi" name="lokasi">
    <input type="hidden" id="foto" name="foto" value="">

</form>
<script>
    function handleSubmit() {
        document.getElementById('absenForm').submit();
    }
</script>
<script src="<?= base_url()  ?>/users/js/kamera.js"></script>