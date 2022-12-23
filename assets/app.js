import './styles/app.scss';
import {dom, library} from '@fortawesome/fontawesome-svg-core';
import {faStar as faStarSolid} from '@fortawesome/free-solid-svg-icons';
import {faStar as faStarRegular} from '@fortawesome/free-regular-svg-icons';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

require('bootstrap');
/* This code is needed to properly load the images in the Leaflet CSS */
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

library.add(
    faStarRegular,
    faStarSolid
);

dom.watch();