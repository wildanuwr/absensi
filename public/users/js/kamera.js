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

    // Konversi dataURL ke Blob
    var blob = dataURLToBlob(dataURL);
    var file = new File([blob], 'foto_' + Date.now() + '.png', { type: 'image/png' });

    var jamMasukInput = document.querySelector('input[name="jam_masuk"]');
    var jamKeluarInput = document.getElementById('jam_keluar');
    var currentDate = new Date().toISOString().split('T')[0];  // Format date YYYY-MM-DD
    var nama = document.querySelector('input[name="Nama"]').value;

    // Cek apakah ada jam_masuk untuk nama dan tanggal hari ini
    fetch(`cek_jam_masuk?nama=${encodeURIComponent(nama)}&date=${encodeURIComponent(currentDate)}`)
        .then(response => response.json())
        .then(data => {
            console.log('Data jam_masuk:', data);  // Log response dari server

            if (data.jam_masuk) {
                // Jika ada jam masuk, maka set jam keluar
                jamKeluarInput.value = new Date().toLocaleTimeString('id-ID', { hour12: false });
            } else {
                // Jika tidak ada, maka set jam masuk
                jamMasukInput.value = new Date().toLocaleTimeString('id-ID', { hour12: false });
            }

            console.log('Jam Masuk:', jamMasukInput.value);  // Log jam masuk
            console.log('Jam Keluar:', jamKeluarInput.value);  // Log jam keluar

            // Melanjutkan ke proses geolocation dan pengiriman data
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Set lokasi value as "latitude,longitude"
                    document.getElementById('lokasi').value = pos.lat + ',' + pos.lng;

                    var formData = new FormData();
                    formData.append('foto', file);
                    formData.append('Nama', nama);
                    formData.append('jam_masuk', jamMasukInput.value);
                    formData.append('jam_keluar', jamKeluarInput.value);
                    formData.append('lokasi', document.querySelector('input[name="lokasi"]').value);

                    fetch("submit_absen", {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())  // Gunakan text() untuk sementara
                    .then(result => {
                        try {
                            let jsonResponse = JSON.parse(result);  // Coba parse sebagai JSON
                            console.log(jsonResponse);
                            alert(jsonResponse.message);
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            console.log(result);  // Tampilkan respon yang tidak ter-parse
                            alert('Respon dari server tidak valid.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim data.');
                    });

                }, function () {
                    alert('Error: The Geolocation service failed.');
                });
            } else {
                alert('Error: Your browser doesn\'t support geolocation.');
            }
        })
        .catch(error => {
            console.error('Error checking jam_masuk:', error);
            alert('Terjadi kesalahan saat memeriksa jam masuk.');
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
        navigator.geolocation.watchPosition(function(position) {
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