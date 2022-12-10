import Shuffle from 'shufflejs';
import {Modal} from "bootstrap/dist/js/bootstrap.bundle";
import L from "leaflet";
import './vendor/rSlider';

const shuffleInstance = new Shuffle(document.getElementById('routeContainer'), {
    itemSelector: '.grid-item'
});

// use value of search field to filter
var quicksearch = document.getElementById('routeNameSearch');
quicksearch.addEventListener( 'keyup', debounce( function() {
    // qsRegex = new RegExp( quicksearch.value, 'gi' );
    shuffleInstance.filter(function (element) {
        const titleElement = element.getAttribute('data-name');
        const titleText = titleElement.toLowerCase().trim();
        return titleText.indexOf(quicksearch.value.toLowerCase()) !== -1;
    })
}, 200 ) );

// debounce so filtering doesn't happen every millisecond
function debounce( fn, threshold ) {
    var timeout;
    threshold = threshold || 100;
    return function debounced() {
        clearTimeout( timeout );
        var args = arguments;
        var _this = this;
        function delayed() {
            fn.apply( _this, args );
        }
        timeout = setTimeout( delayed, threshold );
    };
}

var checkboxes = document.querySelectorAll("input[type=checkbox][name=locations]");
let checkedLocations = []

checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        checkedLocations =
            Array.from(checkboxes) // Convert checkboxes to an array to use filter and map.
                .filter(i => i.checked) // Use Array.filter to remove unchecked checkboxes.
                .map(i => i.value) // Use Array.map to extract only the checkbox values from the array of objects.
        console.log(checkedLocations);

        shuffleInstance.filter(function (element) {
            console.log(element);
            let elementLocations = element.getAttribute('data-locations').split(', ');
            let keepItem = false;
            if (checkedLocations.length > 0) {
                checkedLocations.forEach(function(value) {
                    console.log(elementLocations);
                    keepItem = elementLocations.includes(value);
                });
            } else {
                keepItem = true;
            }
            return keepItem;
        });
    })
});

const slider3 = new rSlider({
    target: '#distanceSearch',
    values: {min: 0, max: 24},
    step: 2,
    range: true,
    set: [0, 24],
    scale: true,
    labels: true,
    tooltip: false,
    onChange: function(values) {
        let specifiedLength = values.split(',');

        shuffleInstance.filter(function (element) {
            console.log(element);
            let elementDistances = element.getAttribute('data-distance').split(', ');
            let keepItem = false;
            elementDistances.forEach(function(value) {
                let floatValue = parseFloat(value);

                if (floatValue >= parseFloat(specifiedLength[0]) && floatValue <= parseFloat(specifiedLength[1])) {
                    keepItem = true;
                }
            });
            return keepItem;
        });
        console.log(values);
    }
});

var myModal = new Modal(document.getElementById('saturdayModal'));
myModal.show();

document.addEventListener('DOMContentLoaded', function(event) {
    const maps = {};
    const routeCards = document.querySelectorAll(".grid-item");
    routeCards.forEach(function (element) {
        let routeSlug = element.getAttribute('data-slug');
        let routeGeoJSONPath = element.getAttribute('data-geojson-path');

        maps['map-' + routeSlug] = L.map('map-' + routeSlug, {zoomControl: false}).setView([37.9963535, -87.6138192], 12);

        fetch(routeGeoJSONPath)
            .then((response) => response.json())
            .then(function (data) {
                let map = maps['map-' + data.slug];

                L.geoJSON(data.geojson, {
                    pointToLayer: function (feature, latlng) {
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
    });
});