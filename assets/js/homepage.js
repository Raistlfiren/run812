import Shuffle from 'shufflejs';
import './vendor/rSlider';
import "../styles/homepage.scss";

document.addEventListener('DOMContentLoaded', function(event) {
    const distanceSlider = document.getElementById('distanceSearch');
    const minDistance = Math.round(Math.floor(distanceSlider.getAttribute('data-min')));
    const maxDistance = Math.round(Math.ceil(distanceSlider.getAttribute('data-max')));
    let routeName = '';
    let routeLocations = [];
    let routeDistances = {'min': null, 'max': null};
    let routeRunningGroups = [];

    const shuffleInstance = new Shuffle(document.getElementById('routeContainer'), {
        itemSelector: '.grid-item'
    });

    function routeNameFilter(element)
    {
        const titleElement = element.getAttribute('data-name');
        const titleText = titleElement.toLowerCase().trim();
        return titleText.includes(routeName);
    }

    function routeLocationFilter(element)
    {
        let keepItem = true;

        if (routeLocations.length > 0) {
            let elementLocations = element.getAttribute('data-locations').split(', ');
            keepItem = false;

            routeLocations.every(function(value) {
                keepItem = elementLocations.includes(value);
                return !keepItem;
            });
        }

        return keepItem;
    }

    function routeRunningGroupsFilter(element)
    {
        let keepItem = true;

        if (routeRunningGroups.length > 0) {
            let elementRunningGroups = element.getAttribute('data-groups').split(', ');
            keepItem = false;

            routeRunningGroups.every(function(value) {
                keepItem = elementRunningGroups.includes(value);
                return !keepItem;
            });
        }

        return keepItem;
    }

    function routeDistanceFilter(element)
    {
        let keepItem = true;

        if (routeDistances.min !== null) {
            let elementDistances = element.getAttribute('data-distance').split(', ');
            keepItem = false;

            elementDistances.forEach(function(value) {
                let floatValue = parseFloat(value);

                if (floatValue >= parseFloat(routeDistances.min) && floatValue <= parseFloat(routeDistances.max)) {
                    keepItem = true;
                }
            });
        }

        return keepItem;
    }

    function filterItems()
    {
        shuffleInstance.filter(function (element) {
            if (
                routeNameFilter(element) &&
                routeRunningGroupsFilter(element) &&
                routeLocationFilter(element) &&
                routeDistanceFilter(element)
            ) {
                return true;
            }
            return false;
        })
    }

    // use value of search field to filter
    const quicksearch = document.getElementById('routeNameSearch');
    quicksearch.addEventListener( 'keyup', debounce( function() {
        routeName = quicksearch.value.toLowerCase();
        filterItems();
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

    const locationCheckboxes = document.querySelectorAll("input[type=checkbox][name=locations]");
    locationCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            routeLocations =
                Array.from(locationCheckboxes) // Convert checkboxes to an array to use filter and map.
                    .filter(i => i.checked) // Use Array.filter to remove unchecked checkboxes.
                    .map(i => i.value) // Use Array.map to extract only the checkbox values from the array of objects.
            filterItems();
        })
    });

    const runningGroupCheckboxes = document.querySelectorAll("input[type=checkbox][name=runningGroup]");
    runningGroupCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            routeRunningGroups =
                Array.from(runningGroupCheckboxes) // Convert checkboxes to an array to use filter and map.
                    .filter(i => i.checked) // Use Array.filter to remove unchecked checkboxes.
                    .map(i => i.value) // Use Array.map to extract only the checkbox values from the array of objects.
            filterItems();
        })
    });

    const slider3 = new rSlider({
        target: '#distanceSearch',
        values: {min: minDistance, max: maxDistance},
        step: 2,
        range: true,
        set: [minDistance, maxDistance],
        scale: true,
        labels: true,
        tooltip: false,
        onChange: function(values) {
            let specifiedLength = values.split(',');
            routeDistances.min = specifiedLength[0];
            routeDistances.max = specifiedLength[1];
            filterItems();
        }
    });
}, {passive: true});