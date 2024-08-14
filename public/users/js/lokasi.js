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