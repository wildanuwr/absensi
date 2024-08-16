<div class="modal fade" id="ModalLokasi" tabindex="-1" aria-labelledby="ModalLokasi" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLokasiLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/simpanlokasi') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="Nama">Nama Kantor</label>
                        <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" required>
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" readonly>
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" readonly>
                    </div>
                    <div class="form-group">
                        <label for="radius">Radius</label>
                        <input type="text" class="form-control" id="radius" name="radius" required>
                    </div>
                    <div style="height: 20rem;" id="map"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function initMap() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                // Inisialisasi peta
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: pos
                });

                // Tambahkan marker ke peta dan buat marker bisa digeser
                var marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    draggable: true // Membuat marker bisa digeser
                });

                // Isi form input dengan latitude dan longitude awal
                document.getElementById('latitude').value = pos.lat;
                document.getElementById('longitude').value = pos.lng;

                // Tambahkan event listener untuk update form input saat marker digeser
                google.maps.event.addListener(marker, 'dragend', function(event) {
                    document.getElementById('latitude').value = event.latLng.lat();
                    document.getElementById('longitude').value = event.latLng.lng();
                });

            }, function() {
                handleLocationError(true);
            });
        } else {
            handleLocationError(false);
        }
    }

    function handleLocationError(browserHasGeolocation) {
        alert(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
    }

    // Inisialisasi peta
    initMap();
</script>