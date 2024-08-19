var video = document.getElementById('video');

// Ambil stream dari kamera
if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
        video.srcObject = stream;
        video.play();
    }).catch(function(error) {
        console.error('Error accessing the camera:', error);
        alert('Tidak dapat mengakses kamera.');
    });
}

// Menangani pengambilan foto dan pengiriman form
function handleSubmit() {
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    var dataURL = canvas.toDataURL('image/png');

    var blob = dataURLToBlob(dataURL);
    var file = new File([blob], 'foto_' + Date.now() + '.png', { type: 'image/png' });

    var nama = document.querySelector('input[name="Nama"]').value;
    var currentDate = new Date().toISOString().split('T')[0];

    fetch(`cek_jam_masuk?nama=${encodeURIComponent(nama)}&date=${encodeURIComponent(currentDate)}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'already_absent') {
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: data.message,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'dashboard';
                }
            });
            return;
        }

        // Jika ada jam masuk, set jam keluar
        var jamMasukInput = document.querySelector('input[name="jam_masuk"]');
        var jamKeluarInput = document.getElementById('jam_keluar');

        if (data.status === 'has_in') {
            jamKeluarInput.value = new Date().toLocaleTimeString('id-ID', { hour12: false });
        } else {
            jamMasukInput.value = new Date().toLocaleTimeString('id-ID', { hour12: false });
        }

        // Dapatkan lokasi pengguna dan lanjutkan ke submit_absen
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                var formData = new FormData();
                formData.append('foto', file);
                formData.append('Nama', nama);
                formData.append('jam_masuk', jamMasukInput.value);
                formData.append('jam_keluar', jamKeluarInput.value);
                formData.append('latitude', pos.lat);
                formData.append('longitude', pos.lng);

                fetch("submit_absen", {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(jsonResponse => {
                    if (jsonResponse.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: jsonResponse.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'dashboard';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: jsonResponse.message,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan!',
                        text: 'Terjadi kesalahan saat mengirim data.',
                        confirmButtonText: 'OK'
                    });
                });

            }, function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan!',
                    text: 'Layanan Geolokasi gagal.',
                    confirmButtonText: 'OK'
                });
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan!',
                text: 'Peramban Anda tidak mendukung geolokasi.',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan!',
            text: 'Terjadi kesalahan saat memeriksa jam masuk.',
            confirmButtonText: 'OK'
        });
    });
}



// Konversi data URL ke Blob
function dataURLToBlob(dataURL) {
    var byteString = atob(dataURL.split(',')[1]);
    var mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], { type: mimeString });
}

// Inisialisasi dan tambahkan peta
function initMap() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: pos
            });

            var marker = new google.maps.Marker({
                position: pos,
                map: map
            });
        }, function() {
            handleLocationError(true);
        });
    } else {
        handleLocationError(false);
    }
}

function handleLocationError(browserHasGeolocation) {
    alert(browserHasGeolocation
        ? 'Error: The Geolocation service failed.'
        : 'Error: Your browser doesn\'t support geolocation.');
}

// Inisialisasi peta
initMap();