import React, {useRef} from 'react';
import {GeoJSON, MapContainer, TileLayer, useMap} from 'react-leaflet';
import axios from 'axios';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});


const polyline = [
    [37.97517, -87.57098],
    [37.97527, -87.57077],
    [37.97562, -87.5700899],
];

const routeOptions = { color: 'red', weight: 5 };

class Map extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            isLoading: true,
            key: 0,
            bounds: null,
            center: [37.969123606775966, -87.57426072712356]
        };

        this.map = useRef();
    }

    componentDidMount() {
        this.setState({ isLoading: true });

        axios.get('http://localhost/routes/29567e0d-100b-4026-8b35-33d768c80513')
            .then(result => {
                this.setState({
                    track: result.data.route,
                    center: [result.data.center.latitude, result.data.center.longitude],
                    bounds: [[result.data.bounds.northEast.latitude, result.data.bounds.northEast.longitude], [result.data.bounds.southWest.latitude, result.data.bounds.southWest.longitude]],
                    key: 1,
                    isLoading: false
                });
                this.map.fitBounds(this.state.bounds);
            })
            .catch(error => this.setState({
                error,
                isLoading: false
            }));
    }

    fitToCustomLayer() {
        const map = useMap();
        if (map) { //we will get inside just once when loading
            map.fitBounds(layer.getBounds().pad(0.5));
        }
    }

    render() {
        return (
            <MapContainer ref={this.map} className="map">
                <TileLayer
                    attribution='&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                />
                {/*<Polyline pathOptions={routeOptions} positions={polyline} />*/}
                {/*<Marker position={[51.505, -0.09]}>*/}
                {/*    <Popup>*/}
                {/*        A pretty CSS3 popup. <br /> Easily customizable.*/}
                {/*    </Popup>*/}
                {/*</Marker>*/}
                <GeoJSON data={this.state.track} key={this.state.key} style={routeOptions} />
            </MapContainer>
        );
    }
}

export default Map;