import "../styles/route-view.scss";
import L from "leaflet";

document.addEventListener('DOMContentLoaded', function(event) {
    const mapElement = document.getElementById('map');
    var map = L.map('map').setView([37.9963535,-87.6138192], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    fetch(mapElement.getAttribute('data-geojson-path'))
        .then((response) => response.json())
        .then(function (data) {
            L.geoJSON(data.geojson, {
                pointToLayer: function(feature, latlng) {
                    var marker = L.marker(latlng, {icon: L.icon({iconUrl: '/images/pin-icon-end.png'})});

                    if (feature.properties.name == 'start') {
                        marker = L.marker(latlng, {icon: L.icon({iconUrl: '/images/pin-icon-start.png'})});
                    }

                    return marker;
                }
            }).addTo(map);

            let bbox = data.geojson.features[0].bbox;

            map.fitBounds([[bbox[0], bbox[1]], [bbox[2], bbox[3]]]);
        });

    let directionLists = document.querySelectorAll(".direction");

    directionLists.forEach(function(item) {
        item.addEventListener('mouseenter', function(event) {
            map.closePopup();
            let latitude = event.target.getAttribute('data-latitude');
            let longitude = event.target.getAttribute('data-longitude');
            let directions = event.target.getAttribute('data-directions');
            console.log(latitude, longitude, directions);

            L.popup()
                .setLatLng([longitude, latitude])
                .setContent(directions)
                .openOn(map);
        });
    });

    directionLists.forEach(function(item) {
        item.addEventListener('mouseleave', function(event) {
            map.closePopup();
        });
    });

    const button = document.getElementById('collapse-navigation');

    button.addEventListener('click', function(event){
        setTimeout(function(){
            map.invalidateSize(true);
            map.fitBounds([[bbox[0], bbox[1]], [bbox[2], bbox[3]]]);
        }, 400);
    });
});