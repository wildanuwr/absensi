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

<h1>Lokasi Saat Ini</h1>
<div style="height: 50rem;" id="map"></div>
<script src="<?= base_url()  ?>/users/js/lokasi.js"></script>